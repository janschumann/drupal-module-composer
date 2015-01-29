<?php

namespace Drupal\ModuleComposer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends BaseKernel {

  /**
   * @var string
   */
  private $configDir = '';

  /**
   * Constructor.
   *
   * @param string  $environment
   * @param bool    $debug
   * @param string  $rootDir      The root path of all files this kernel creates
   * @param string  $configDir    The directory to find configuration files
   */
  public function __construct($environment, $debug, $rootDir, $configDir)
  {
    $this->name = "DrushModuleComposer";
    $this->rootDir = $rootDir;
    $this->configDir = $configDir;

    parent::__construct($environment, $debug);
  }

  /**
   * {@inheritdoc}
   */
  public function registerBundles()
  {
    return array(new \Drupal\ModuleComposer\Bundle\ModuleComposerBundle\ModuleComposerBundle());
  }

  /**
   * {@inheritdoc}
   */
  public function registerContainerConfiguration(LoaderInterface $loader)
  {
    // try to load environment specific file
    $prefix = $this->configDir . '/' . 'module_composer_settings';
    $settings = $prefix . '_' . $this->getEnvironment() . '.xml';

    if (file_exists($settings)) {
      $loader->load($settings);
    }
    else {
      // load general file as a fallback
      $settings = $prefix . '.xml';
      if (file_exists($settings)) {
        $loader->load($settings);
      }
    }
  }

  /**
   * Consider
   * - Server Parameters prefixed with DRUPAL__
   * - Constants prefixed with DRUPAL_
   *
   * {@inheritdoc}
   */
  protected function getEnvParameters() {
    $parameters = array();

    // add config dir as a parameter
    $parameters['kernel.config_dir'] = $this->configDir;

    // add server variables
    foreach ($_SERVER as $key => $value) {
      if (0 === strpos($key, 'DRUPAL__')) {
        $parameters[strtolower(str_replace('__', '.', substr($key, 8)))] = $value;
      }
    }

    // add constants
    $constants = get_defined_constants(true);
    foreach ($constants['user'] as $key => $value) {
      if (0 === strpos($key, strtoupper($this->name) . '_')) {
        $parameters[strtolower(str_replace('_', '.', $key))] = $value;
      }
    }

    return $parameters;
  }

  /**
   * We do not handle requests
   */
  public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
  {
  }

}

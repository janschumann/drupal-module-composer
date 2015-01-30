<?php

namespace Drupal\ModuleComposer\Bundle\ModuleComposerBundle\DependencyInjection;

use Drupal\ModuleComposer\Project;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class ModuleComposerExtension extends Extension
{
  /**
   * Responds to the app.config configuration parameter.
   *
   * @param array            $configs
   * @param ContainerBuilder $container
   */
  public function load(array $configs, ContainerBuilder $container)
  {
    $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

    $loader->load('services.xml');

    $configuration = $this->getConfiguration($configs, $container);
    $config = $this->processConfiguration($configuration, $configs);

    foreach ($config['drushExtensions'] as $projectName => $project) {
      $this->buildProject($container, Project::TYPE_CONTRIB, $projectName, $project);
    }

    foreach ($config['coreProjects']['projects'] as $projectName => $project) {
      $this->buildProject($container, Project::TYPE_CORE, $projectName, $project);
    }

    foreach ($config['contribProjects']['projects'] as $projectName => $project) {
      $this->buildProject($container, Project::TYPE_CONTRIB, $projectName, $project);
    }

    foreach ($config['customProjects'] as $typeName => $type) {
      foreach ($type['projects'] as $projectName => $project) {
        $this->buildProject($container, $typeName, $projectName, $project);
      }
    }
  }

  private function buildProject(ContainerBuilder $container, $type, $projectName, $project) {
    $projectDefinition = new DefinitionDecorator('mc.project');
    $projectDefinition->addMethodCall('setName', array($projectName));
    $projectDefinition->addMethodCall('setVersion', array($project['version']));
    $projectDefinition->addMethodCall('setType', array('module'));
    $projectDefinition->addMethodCall('setSubType', array($type));
    $container->setDefinition('mc.project.' . $projectName, $projectDefinition);
    foreach ($project['modules'] as $moduleName => $module) {
      $moduleDefinition = new DefinitionDecorator('mc.module');
      $moduleDefinition->addMethodCall('setName', array($moduleName));
      $moduleDefinition->addMethodCall('setState', array($module['state']));
      $moduleDefinition->addMethodCall('setProject', array(new Reference('mc.project.' . $projectName)));
      $container->setDefinition('mc.module.' . $moduleName, $moduleDefinition);
      $projectDefinition->addMethodCall('addModule', array(new Reference('mc.module.' . $moduleName)));
    }

    $projectDefinition->addTag('mc.configured_project');
  }

  /**
   * Returns the base path for the XSD files.
   *
   * @return string The XSD base path
   */
  public function getXsdValidationBasePath()
  {
    return __DIR__.'/../Resources/config/schema';
  }

  public function getNamespace()
  {
    return 'http://drupal.org/schema/module-composer/module-composer';
  }
}

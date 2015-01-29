<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 20.01.15
 * Time: 14:03
 */

namespace Drupal\Bridge;


class DrushBridge {

  /**
   * Drush commands
   *
   * @var array
   */
  private $commands = array();
  /**
   * Drupal extensions
   *
   * @var array
   */
  private $extensions = array();

  /**
   * Constructor loads available drush commands.
   */
  public function __construct() {
    $this->commands = drush_get_commands();
  }

  /**
   * @param $command
   * @param array $arguments
   * @return bool|mixed
   */
  public function invoke($command, $arguments = array()) {
    if (!array_key_exists($command, $this->commands)) {
      throw new \RuntimeException("Drush command $command not found.");
    }

    $this->commands[$command]['arguments'] = array();
    $result = drush_dispatch($this->commands[$command], $arguments);

    if (drush_get_error()) {
      throw new \RuntimeException('Drush invoke error occured.');
    }

    return $result;
  }

  /**
   * @return array
   */
  public function getExtensions() {
    if (empty($this->extensions)) {
      $this->extensions = drush_get_extensions(false);
    }

    return $this->extensions;
  }

  /**
   * @param \stdClass $extension
   * @return string
   */
  public function getExtensionStatus(\stdClass $extension) {
    return drush_get_extension_status($extension);
  }
}

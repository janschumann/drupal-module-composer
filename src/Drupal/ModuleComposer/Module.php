<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 20.01.15
 * Time: 17:11
 */

namespace Drupal\ModuleComposer;


class Module {

  const STATE_ENABLED = 'enabled';
  const STATE_DISABLED = 'disabled';
  const STATE_UNINSTALLED = 'not installed';

  /**
   * @var string
   */
  private $name;
  /**
   * @var string
   */
  private $state;
  /**
   * @var string
   */
  private $filename;
  /**
   * @var Project
   */
  private $project;
  /**
   * @var Module[]
   */
  private $dependencies = array();
  /**
   * @var Module[]
   */
  private $requiredBy = array();

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * @return string
   */
  public function getState() {
    return $this->state;
  }

  /**
   * @param string $state
   */
  public function setState($state) {
    $this->state = $state;
  }

  /**
   * @param bool $set
   * @return bool
   */
  public function isEnabled($set = false) {
    if ($set === true) {
      $this->setState(static::STATE_ENABLED);
    }
    return $this->hasState(static::STATE_ENABLED);
  }

  /**
   * @param bool $set
   * @return bool
   */
  public function isDisabled($set = false) {
    if ($set === true) {
      $this->setState(static::STATE_DISABLED);
    }
    return $this->hasState(static::STATE_DISABLED);
  }

  /**
   * @param bool $set
   * @return bool
   */
  public function isUninstalled($set = false) {
    if ($set === true) {
      $this->setState(static::STATE_UNINSTALLED);
    }
    return $this->hasState(static::STATE_UNINSTALLED);
  }

  /**
   * @param string $state
   * @return bool
   */
  private function hasState($state) {
    return $this->getState() === $state;
  }

  /**
   * @return string
   */
  public function getFilename() {
    return $this->filename;
  }

  /**
   * @param string $filename
   */
  public function setFilename($filename) {
    $this->filename = $filename;
  }

  /**
   * @return string
   */
  public function getType() {
    return $this->getProject()->getType();
  }

  /**
   * @return string
   */
  public function getVersion() {
    return $this->getProject()->getVersion();
  }

  /**
   * @return Project
   */
  public function getProject() {
    return $this->project;
  }

  /**
   * @param Project $project
   */
  public function setProject($project) {
    $this->project = $project;
  }

  /**
   * @param Module $module
   */
  public function addDependency(Module $module) {
    $this->dependencies[] = $module;
  }

  /**
   * @param Module $module
   */
  public function addRequiredByModule(Module $module) {
    $this->requiredBy[] = $module;
  }

  /**
   * @return Module[]
   */
  public function getDependencies() {
    return $this->dependencies;
  }

  /**
   * @return bool
   */
  public function hasDependencies() {
    return 0 < count($this->dependencies);
  }

  /**
   * @return Module[]
   */
  public function getRequiredBy() {
    return $this->requiredBy;
  }

  /**
   * @return bool
   */
  public function hasRequiredBy() {
    return 0 < count($this->requiredBy);
  }
}

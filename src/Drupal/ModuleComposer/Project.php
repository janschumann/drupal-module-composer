<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 20.01.15
 * Time: 13:20
 */

namespace Drupal\ModuleComposer;


class Project {

  const TYPE_CORE = 'core';
  const TYPE_CONTRIB = 'contrib';

  /**
   * @var string
   */
  private $name;
  /**
   * @var string
   */
  private $version;
  /**
   * @var string
   */
  private $type;
  /**
   * @var string
   */
  private $subType;
  /**
   * @var Module[]
   */
  private $modules = array();

  /**
   * @param string $name
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * @param string $version
   */
  public function setVersion($version) {
    $this->version = (string) $version;
  }

  /**
   * @return string
   */
  public function getVersion() {
    return (string) $this->version;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @return string
   */
  public function getSubType() {
    return $this->subType;
  }

  /**
   * @param string $subType
   */
  public function setSubType($subType) {
    $this->subType = $subType;
  }

  /**
   * @param string $extensionType
   */
  public function setType($extensionType) {
    $this->type = $extensionType;
  }

  public function addModule(Module $module) {
    $this->modules[$module->getName()] = $module;
  }

  /**
   * @return Module[]
   */
  public function getModules() {
    ksort($this->modules);
    return $this->modules;
  }

  /**
   * @return bool
   */
  public function isCore() {
    return $this->subType == static::TYPE_CORE;
  }

  /**
   * @return bool
   */
  public function isContrib() {
    return $this->subType == static::TYPE_CONTRIB;
  }
}

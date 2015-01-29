<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 20.01.15
 * Time: 16:43
 */

namespace Drupal\ModuleComposer\Project;

use Drupal\ModuleComposer\Module;
use Drupal\ModuleComposer\Project;

/**
 * Class ProjectRegistry
 * @package Drupal\ModuleComposer\Project
 */
class ProjectRegistry {

  /**
   * @var Project[]
   */
  private $customProjects = array();
  /**
   * @var Project[]
   */
  private $coreProjects = array();
  /**
   * @var Project[]
   */
  private $contribProjects = array();
  /**
   * @var Module[]
   */
  private $modules = array();

  /**
   * @param Project $project
   */
  public function addProject(Project $project) {
    if ($project->isCore()) {
      if(!array_key_exists($project->getName(), $this->coreProjects)) {
        $this->coreProjects[$project->getName()] = $project;
      }
    }
    elseif ($project->isContrib()) {
      if(!array_key_exists($project->getName(), $this->contribProjects)) {
        $this->contribProjects[$project->getName()] = $project;
      }
    }
    else {
      if(!array_key_exists($project->getName(), $this->customProjects)) {
        $this->customProjects[$project->getName()] = $project;
      }
    }
  }

  /**
   * @param Module $module
   */
  public function addModule(Module $module) {
    if(!array_key_exists($module->getName(), $this->modules)) {
      $this->modules[$module->getName()] = $module;
    }
  }

  /**
   * @return Project[]
   */
  public function getCoreProjects() {
    ksort($this->coreProjects);
    return $this->coreProjects;
  }

  /**
   * @return Project[]
   */
  public function getContribProjects() {
    ksort($this->contribProjects);
    return $this->contribProjects;
  }

  /**
   * @return Project[]
   */
  public function getCustomProjects() {
    ksort($this->customProjects);
    return $this->customProjects;
  }

  /**
   * @param $name
   * @return bool
   */
  public function hasProject($name) {
    return array_key_exists($name, $this->coreProjects) || array_key_exists($name, $this->contribProjects) || array_key_exists($name, $this->customProjects);
  }

  /**
   * @param $name
   * @return Project
   */
  public function getProject($name) {
    if (!$this->hasProject($name)) {
      throw new \RuntimeException('Project ' . $name . ' is not registered.');
    }

    if (array_key_exists($name, $this->coreProjects)) {
      return $this->coreProjects[$name];
    }

    if (array_key_exists($name, $this->contribProjects)) {
      return $this->contribProjects[$name];
    }

    return $this->customProjects[$name];
  }

  /**
   * @param $name
   * @return bool
   */
  public function hasModule($name) {
    return array_key_exists($name, $this->modules);
  }

  /**
   * @param $name
   * @return Module
   */
  public function getModule($name) {
    if (!$this->hasModule($name)) {
      throw new \RuntimeException('Module ' . $name . ' is not registered.');
    }
    return $this->modules[$name];
  }

  public function getModules() {
    return $this->modules;
  }
}

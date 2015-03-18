<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 20.01.15
 * Time: 13:16
 */

namespace Drupal\ModuleComposer;


use Drupal\ModuleComposer\Project\ProjectCollection;
use Drupal\ModuleComposer\Project\ProjectRegistry;

class Composer {

  /**
   * @var array
   */
  private $customProjectsTypeOrder = array('composer', 'dev', 'custom');
  /**
   * @var array
   */
  private $toDo = array(
    'download' => array(),
    'disable' => array(),
    'uninstall' => array(),
    'enable' => array()
  );
  /**
   * @var ProjectRegistry
   */
  private $installedProjects;

  /**
   * @param ProjectRegistry $installedProjects
   */
  public function __construct(ProjectRegistry $installedProjects) {
    $this->installedProjects = $installedProjects;
  }

  /**
   * @return Project[]
   */
  public function getCoreProjects() {
    return $this->installedProjects->getCoreProjects();
  }

  /**
   * @return Project[]
   */
  public function getContribProjects() {
    return $this->installedProjects->getContribProjects();
  }

  /**
   * @return array
   */
  public function getCustomProjectsBySubType() {
    $types = $this->getProjectsBySubType($this->installedProjects->getCustomProjects());

    $return = array();
    foreach ($this->getOrder() as $type) {
      if (isset($types[$type])) {
        $return[$type] = $types[$type];
      }
    }

    return $return;
  }

  /**
   * @param Project[] $projects
   * @return array
   */
  private function getProjectsBySubType($projects) {
    $orderedProjects = array();
    foreach($projects as $project) {
      $type = $project->getSubType();
      if (!array_key_exists($type, $orderedProjects)) {
        $orderedProjects[$type] = array();
      }
      $orderedProjects[$type][] = $project;
    }

    return $orderedProjects;
  }

  /**
   * @param array $order
   */
  public function setOrder(array $order) {
    $this->customProjectsTypeOrder = $order;
  }

  /**
   * @return array
   */
  public function getOrder() {
    return $this->customProjectsTypeOrder;
  }

  /**
   * @param ProjectRegistry $configuredProjects
   * @return array
   */
  public function getToDo(ProjectRegistry $configuredProjects) {
    $this->processProjects($configuredProjects->getCoreProjects(), false, true);

    $this->processProjects($configuredProjects->getContribProjects(), true);

    $customTypes = $this->getProjectsBySubType($configuredProjects->getCustomProjects());
    foreach ($this->getOrder() as $type) {
      if (isset($customTypes[$type])) {
        $this->processProjects($customTypes[$type]);
      }
    }

    return $this->toDo;
  }

  /**
   * @param Project[] $projects       The projects to process
   * @param bool      $fixVersion     Whether to download the configured project (only contrib)
   * @param bool      $noInstallCheck Whether to check installed module (true for core projects)
   */
  private function processProjects($projects, $fixVersion = false, $noInstallCheck = false) {
    $enable = $disable = $uninstall = $download = array();
    foreach ($projects as $name => $project) {
      if ($fixVersion && (!$this->installedProjects->hasProject($name) || $project->getVersion() !== $this->installedProjects->getProject($name)->getVersion())) {
        $version = $project->getVersion();
        if ("" === $version) {
          $download[] = $name;
        }
        else {
          $download[] = $name . '-' . $version;
        }
      }
      foreach ($project->getModules() as $moduleName => $module) {
        if ($noInstallCheck || $this->installedProjects->hasModule($moduleName)) {
          $installed = $this->installedProjects->getModule($moduleName);
          if ($module->isEnabled() && ($installed->isUninstalled() || $installed->isdisabled())) {
            $enable[] = $moduleName;
          }
          elseif ($module->isdisabled() && $installed->isEnabled()) {
            $disable[] = $moduleName;
          }
          elseif ($module->isUninstalled() && ($installed->isEnabled() || $installed->isdisabled())) {
            $disable[] = $moduleName;
            $uninstall[] = $moduleName;
          }
        }
        else {
          if ($module->isEnabled()) {
            $enable[] = $moduleName;
          }
        }
      }
    }
    if (!empty($download)) {
      $this->toDo['download'][] = $download;
    }
    if (!empty($enable)) {
      $this->toDo['enable'][] = $enable;
    }
    if (!empty($disable)) {
      $this->toDo['disable'][] = $disable;
    }
    if (!empty($uninstall)) {
      $this->toDo['uninstall'][] = $uninstall;
    }
  }
}

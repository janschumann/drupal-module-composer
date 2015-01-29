<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 21.01.15
 * Time: 11:27
 */

namespace Drupal\ModuleComposer\Project;

use Drupal\Bridge\DrushBridge;

class ProjectCollector {

  /**
   * @var DrushBridge
   */
  private $drush;
  /**
   * @var ProjectRegistry
   */
  private $projectRegistry;
  /**
   * @var ProjectFactory
   */
  private $projectFactory;

  /**
   * @param DrushBridge $drush
   * @param ProjectRegistry $registry
   * @param ProjectFactory $factory
   */
  public function __construct(DrushBridge $drush, ProjectRegistry $registry, ProjectFactory $factory) {
    $this->drush = $drush;
    $this->projectRegistry = $registry;
    $this->projectFactory = $factory;
  }

  /**
   * Collect installed projects and add them to the project registry
   */
  public function collect() {
    // register installed projects
    foreach ($this->drush->getExtensions() as $extension) {
      if ($extension->type == 'theme') {
        continue;
      }

      $projectName = $this->projectFactory->getProjectName($extension);
      if ($this->projectRegistry->hasProject($projectName)) {
        $project = $this->projectRegistry->getProject($projectName);
      }
      else {
        $project = $this->projectFactory->createProjectFromExtension($extension);
        $this->projectRegistry->addProject($project);
      }

      $module = $this->projectFactory->createModuleFromExtension($extension);
      $module->setProject($project);
      $project->addModule($module);

      $this->projectRegistry->addModule($module);
    }

    // populate module dependencies
    foreach ($this->drush->getExtensions() as $extension) {
      if ($extension->type == 'theme') {
        continue;
      }

      $module = $this->projectRegistry->getModule($extension->name);
      foreach (array_keys($extension->requires) as $name) {
        if ($this->projectRegistry->hasModule($name)) {
          $module->addDependency($this->projectRegistry->getModule($name));
        }
      }
      foreach (array_keys($extension->required_by) as $name) {
        if ($this->projectRegistry->hasModule($name)) {
          $module->addRequiredByModule($this->projectRegistry->getModule($name));
        }
      }
    }
  }

  /**
   * @return ProjectRegistry
   */
  public function getProjectRegistry() {
    return $this->projectRegistry;
  }
}

<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 20.01.15
 * Time: 16:36
 */

namespace Drupal\ModuleComposer\Project;


use Drupal\Bridge\DrushBridge;
use Drupal\ModuleComposer\Module;
use Drupal\ModuleComposer\Project;

class ProjectFactory {

  /**
   * @var DrushBridge
   */
  private $drush;

  /**
   * @param DrushBridge $drush
   */
  public function __construct(DrushBridge $drush) {
    $this->drush = $drush;
  }

  /**
   * Returns the correct project name of an extension
   *
   * @param \stdClass $extension
   * @return string
   */
  public function getProjectName(\stdClass $extension) {
    $name = "";
    // contrib extensions provide a project property within their ini files
    // if this is provided, we take it
    if (array_key_exists('project', $extension->info)) {
      $name = $extension->info['project'];
    }
    // the project name of custom modules is always equal to the foldername
    elseif ($extension->type == 'module' && false !== strpos($extension->filename, 'sites/all/modules')) {
      // pattern: sites/all/modules/<subtype>/<projectname>
      $parts = explode('/', $extension->filename);
      $name = $parts[4];
    }

    return $name;
  }

  /**
   * @param \stdClass $extension
   * @return Project
   */
  public function createProjectFromExtension(\stdClass $extension) {
    $project = new Project();
    $project->setName($this->getProjectName($extension));
    $project->setVersion($extension->info['version']);
    $project->setType($extension->type);

    // core extensions
    if (($extension->info['package'] == 'Core') || ((array_key_exists('project', $extension->info)) && ($extension->info['project'] == 'drupal'))) {
      $project->setSubType('core');
    }

    // non core extensions have a subtype that is determined by the foldername right after sites/all/modules
    if ($extension->type == 'module' && false !== strpos($extension->filename, 'sites/all/modules')) {
      // pattern: sites/all/modules/<subtype>/<projectname>
      $parts = explode('/', $extension->filename);
      $project->setSubType($parts[3]);
    }

    return $project;
  }

  /**
   * @param \stdClass $extension
   * @return Module
   */
  public function createModuleFromExtension(\stdClass $extension) {
    $module = new Module();
    $module->setName($extension->name);
    $module->setState($this->drush->getExtensionStatus($extension));

    return $module;
  }
}

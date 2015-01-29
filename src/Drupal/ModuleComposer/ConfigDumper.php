<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 27.01.15
 * Time: 18:32
 */

namespace Drupal\ModuleComposer;


class ConfigDumper {

  /**
   * @var Composer
   */
  private $composer;

  /**
   * @param Composer $profiler
   */
  public function __construct(Composer $composer) {
    $this->composer = $composer;
  }

  /**
   * @return string xml config of installed projects
   */
  public function dump() {
    $xml = <<<EOF
<?xml version="1.0" ?>

<container 	xmlns="http://symfony.com/schema/dic/services"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:profiler="http://drupal.org/schema/module-composer/module-composer"
           	xsi:schemaLocation="
           		http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd
           		http://drupal.org/schema/module-composer/module-composer http://drupal.org/schema/module-composer/module-composer/module-composer-1.0.xsd">

	<profiler:config>

EOF;

    $xml .= "\t\t<profiler:coreProjects>\n";
    $this->dumpProjects($this->composer->getCoreProjects(), $xml);
    $xml .= "\t\t</profiler:coreProjects>\n";

    $xml .= "\t\t<profiler:contribProjects>\n";
    $this->dumpProjects($this->composer->getContribProjects(), $xml);
    $xml .= "\t\t</profiler:contribProjects>\n";

    foreach($this->composer->getCustomProjectsBySubType() as $typeName => $projects) {
      $xml .= "\t\t<profiler:customProjects type=\"$typeName\">\n";
      $this->dumpProjects($projects, $xml);
      $xml .= "\t\t</profiler:customProjects>\n";
    }

    $xml .= <<<EOF
	</profiler:config>
</container>

EOF;

    return $xml;
  }

  /**
   * @param Project[] $projects
   * @param string    $xml the xml string to add projects to
   */
  private function dumpProjects($projects, &$xml) {
    /* @var Project $project */
    foreach ($projects as $project) {
      $xml .= "\t\t\t<profiler:project name=\"" . $project->getName() . "\" version=\"" . $project->getVersion() . "\">\n";
      /* @var Module $module */
      foreach ($project->getModules() as $module) {
        $xml .= "\t\t\t\t<profiler:module name=\"" . $module->getName() ."\" state=\"" . $module->getState() . "\" />\n";
      }
      $xml .= "\t\t\t</profiler:project>\n";
    }
  }
}

<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 27.01.15
 * Time: 18:32
 */

namespace Drupal\ModuleComposer;


class VariableDumper {

  /**
   * @return string xml config of installed projects
   */
  public function dump(array $variables) {
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

    foreach($variables as $name => $value) {
      $xml .= "\t\t <profiler:variable name=\"" . $name . "\" value=\"" . $value. "\" />\n";
    }

    $xml .= <<<EOF
	</profiler:config>
</container>

EOF;

    return $xml;
  }
}

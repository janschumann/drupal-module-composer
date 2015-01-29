<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 26.01.15
 * Time: 13:28
 */

namespace Drupal\ModuleComposer\Bundle\ModuleComposerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterProjectsPass  implements CompilerPassInterface {
  /**
   * You can modify the container here before it is dumped to PHP code.
   *
   * @param ContainerBuilder $container
   *
   * @api
   */
  public function process(ContainerBuilder $container) {

    $registry = $container->getDefinition('mc.configured_projects');
    foreach ($container->findTaggedServiceIds('mc.configured_project') as $id => $project) {
      $registry->addMethodCall('addProject', array(new Reference($id)));
    }
  }
}

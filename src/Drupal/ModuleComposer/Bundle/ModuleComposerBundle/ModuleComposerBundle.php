<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 20.01.15
 * Time: 10:25
 */

namespace Drupal\ModuleComposer\Bundle\ModuleComposerBundle;

use Drupal\ModuleComposer\Bundle\ModuleComposerBundle\DependencyInjection\RegisterProjectsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ModuleComposerBundle extends Bundle {

  public function build(ContainerBuilder $container)
  {
    parent::build($container);

    $container->addCompilerPass(new RegisterProjectsPass());
  }
}



<?php

namespace Drupal\ModuleComposer\Bundle\ModuleComposerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

  /**
   * Generates the configuration tree builder.
   *
   * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
   */
  public function getConfigTreeBuilder() {
    $treeBuilder = new TreeBuilder();

    $rootNode = $treeBuilder->root('config');

    $rootNode
      ->fixXmlConfig('drushExtension')
      ->children()
        ->arrayNode('drushExtensions')->addDefaultChildrenIfNoneSet(array())
          ->useAttributeAsKey('name')
          ->prototype('array')
            ->children()
              ->scalarNode('version')->defaultValue('')->end()
            ->end()
          ->end()
        ->end()
        ->arrayNode('coreProjects')->addDefaultsIfNotSet(array())
          ->fixXmlConfig('project')
          ->children()
            ->arrayNode('projects')->addDefaultChildrenIfNoneSet(array())
              ->useAttributeAsKey('name')
              ->prototype('array')
                ->fixXmlConfig('module')
                ->fixXmlConfig('patch')
                ->children()
                  ->arrayNode('modules')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                      ->children()
                        ->scalarNode('state')->isRequired()->end()
                      ->end()
                    ->end()
                  ->end()
                  ->arrayNode('patches')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                      ->children()
                        ->scalarNode('path')->isRequired()->end()
                      ->end()
                    ->end()
                  ->end()
                  ->scalarNode('version')->isRequired()->end()
                ->end()
              ->end()
            ->end()
          ->end()
        ->end()
        ->arrayNode('contribProjects')->addDefaultsIfNotSet(array())
          ->fixXmlConfig('project')
          ->children()
            ->arrayNode('projects')->addDefaultChildrenIfNoneSet(array())
              ->useAttributeAsKey('name')
              ->prototype('array')
                ->fixXmlConfig('module')
                ->fixXmlConfig('patch')
                ->children()
                  ->arrayNode('modules')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                      ->children()
                        ->scalarNode('state')->isRequired()->end()
                      ->end()
                    ->end()
                  ->end()
                  ->arrayNode('patches')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                      ->children()
                        ->scalarNode('path')->isRequired()->end()
                      ->end()
                    ->end()
                  ->end()
                  ->scalarNode('version')->isRequired()->end()
                ->end()
              ->end()
            ->end()
          ->end()
        ->end()
        ->arrayNode('customProjects')
          ->useAttributeAsKey('type')
          ->prototype('array')
            ->fixXmlConfig('project')
            ->children()
              ->arrayNode('projects')
                ->useAttributeAsKey('name')
                ->prototype('array')
                  ->fixXmlConfig('module')
                  ->children()
                    ->arrayNode('modules')
                      ->useAttributeAsKey('name')
                      ->prototype('array')
                        ->children()
                          ->scalarNode('state')->isRequired()->end()
                        ->end()
                      ->end()
                    ->end()
                    ->scalarNode('version')->end()
                  ->end()
                ->end()
              ->end()
            ->end()
          ->end()
        ->end()
      ->end();

    return $treeBuilder;
  }
}

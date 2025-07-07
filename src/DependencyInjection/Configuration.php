<?php

namespace CubeMage\PdfJsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        // 配置的根节点使用新的 Bundle 名称
        $treeBuilder = new TreeBuilder('cube_mage_pdf_js');
        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('default_save')->defaultNull()->end()
            ->end();
        return $treeBuilder;
    }
}
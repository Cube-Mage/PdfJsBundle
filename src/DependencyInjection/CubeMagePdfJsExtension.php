<?php

// 声明文件所在的命名空间
namespace CubeMage\PdfJsBundle\DependencyInjection;

// 引入所有需要用到的类
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * 这是加载和管理你的 Bundle 配置的核心类。
 *
 * Symfony 的内核会调用这个类来处理 `config/packages/cube_mage_pdf_js.yaml` 中的配置。
 * @see https://symfony.com/doc/current/bundles/extension.html
 */
class CubeMagePdfJsExtension extends Extension // 关键：必须继承 Symfony 的 Extension 基类
{
    /**
     * {@inheritdoc}
     *
     * 这个方法是所有配置处理的入口点。
     *
     * @param array $configs 由用户提供的，来自多个配置文件的配置数组
     * @param ContainerBuilder $container Symfony 的服务容器
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        // 步骤 1: 加载并处理配置
        // 这会使用 Configuration.php 中定义的规则来验证和合并用户提供的所有配置。
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // 步骤 2: 加载这个 Bundle 自己的服务定义文件
        // 这会读取 `Resources/config/services.yaml` 文件，并将其中定义的服务注册到容器中。
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        // 步骤 3: 将处理后的配置值传递给你的服务
        // 这一步让你的 Bundle 变得“可配置”。
        // 我们找到 Twig 扩展的服务定义，并将用户配置的值注入到它的构造函数中。
        if ($container->hasDefinition('cubemage_pdfjs.twig_extension')) {
            $definition = $container->getDefinition('cubemage_pdfjs.twig_extension');

            // 将 `default_save_route` 配置项的值，注入到构造函数的 `$defaultSaveRoute` 参数
            $definition->setArgument('$defaultSaveRoute', $config['default_save_route']);

            // 将 `asset_path` 配置项的值，注入到构造函数的 `$assetPath` 参数
            $definition->setArgument('$assetPath', $config['asset_path']);
        }
    }
}
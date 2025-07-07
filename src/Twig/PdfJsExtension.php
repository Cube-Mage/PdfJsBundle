<?php

namespace CubeMage\PdfJsBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PdfJsExtension extends AbstractExtension
{
    private UrlGeneratorInterface $router;
    private ?string $defaultSaveRoute;

    public function __construct(UrlGeneratorInterface $router, ?string $defaultSaveRoute = null)
    {
        $this->router = $router;
        $this->defaultSaveRoute = $defaultSaveRoute;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pdf_editor', [$this, 'renderEditor'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    // 方法已更新
    public function renderEditor(Environment $twig, string $pdfPath, ?string $saveRouteName = null): string
    {
        $finalSaveRoute = $saveRouteName ?? $this->defaultSaveRoute;

        if (null === $finalSaveRoute) {
            throw new \InvalidArgumentException('You must provide a save route for the PDF viewer.');
        }

        // 将路由名称转换为真实的 URL
        $saveUrl = $this->router->generate($finalSaveRoute, [], UrlGeneratorInterface::ABSOLUTE_URL);

        // 直接渲染组件模板，并传入所需变量
        return $twig->render('@CubeMagePdfJs/editor.html.twig', [
            'pdf_url' => $pdfPath,
            'save_url' => $saveUrl,
        ]);
    }
}
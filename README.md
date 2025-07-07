# CubeMage PDF.js Bundle for Symfony

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cubemage/pdfjs-bundle.svg?style=flat-square)](https://packagist.org/packages/cubemage/pdfjs-bundle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/your-github-username/cubemage-pdfjs-bundle/ci.yml?branch=main&style=flat-square)](https://github.com/your-github-username/cubemage-pdfjs-bundle/actions)

一个为 Symfony 设计的轻量级 Bundle，可以轻松地将 Mozilla 的 [PDF.js](https://github.com/mozilla/pdf.js) 查看器集成到你的项目中。它允许你在任何 Twig 模板中渲染一个功能齐全的 PDF 查看器，并支持 PDF 表单数据的提取和保存。

## ✨ 主要功能

-   在任何页面中轻松嵌入 PDF 查看器。
-   使用简单的 Twig 函数进行调用。
-   支持 PDF 内置表单 (AcroForms) 的填写和数据提取。
-   允许自定义数据保存的后端路由，使业务逻辑完全解耦。
-   通过 Symfony 的 `assets` 组件进行标准的资源管理。
-   无缝集成，不引入额外的路由或控制器，避免与主应用冲突。

## 📦 安装

1.  通过 Composer 安装 Bundle：

    ```bash
    composer require cubemage/pdfjs-bundle
    ```

2. 在 `config/bundles.php` 文件中注册 Bundle：

   ```php
   return [
       // ... 其他 bundles
       \CubeMage\PdfJsBundle\src\CubeMagePdfJsBundle::class => ['all' => true],
   ];
   ```

3.  安装 Bundle 的 Web 资源。此命令会将 PDF.js 的前端文件链接到你项目的 `public/` 目录下。

    ```bash
    php bin/console assets:install
    ```

## ⚙️ 配置

你可以选择性地配置一个全局默认的数据保存路由。在 `config/packages/` 目录下创建一个新文件 `cube_mage_pdf_js.yaml`：

```yaml
# config/packages/cube_mage_pdf_js.yaml
cube_mage_pdf_js:
    # 设置一个你项目中用于接收和处理 PDF 表单数据的路由名称
    default_save_route: 'app_save_pdf_data'
```

## 🚀 使用方法

1.  **在你的控制器中，创建用于保存数据的路由**

Bundle 负责前端的展示和数据提交，但你需要自己创建一个 Controller Action 来接收数据并将其存入数据库。

   ```php
   // src/Controller/YourController.php
   namespace App\Controller;
   
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse;
   use Symfony\Component\Routing\Annotation\Route;

   class YourController extends AbstractController
   {
       /**
        * @Route("/save-pdf-data", name="app_save_pdf_data", methods={"POST"})
        */
       public function savePdfData(Request $request): JsonResponse
       {
           $data = json_decode($request->getContent(), true);

           if (json_last_error() !== JSON_ERROR_NONE) {
               return new JsonResponse(['status' => 'error', 'message' => 'Invalid JSON'], 400);
           }
           
           // 在这里处理你的业务逻辑，例如：
           // $entityManager = $this->getDoctrine()->getManager();
           // ... 将 $data 数组中的数据保存到你的实体中 ...
           // $entityManager->flush();

           return new JsonResponse(['status' => 'success', 'message' => 'Data saved!']);
       }
   }
   ```

2.  **在你的 Twig 模板中调用 `pdf_viewer` 函数**

现在，你可以在任何 Twig 模板中轻松地渲染查看器了。

   ```twig
   {% extends 'base.html.twig' %}

   {% block body %}
       <h1>查看我们的合同文档</h1>

       {# 
          第一个参数是 PDF 文件的公共路径，建议使用 asset() 函数生成。
          第二个参数是保存数据的路由名称。如果已在配置文件中设置，则此参数为可选。
        #}
       {{ pdf_viewer(asset('documents/contract.pdf'), 'app_save_pdf_data') }}

       <hr>
       <p>页面的其他内容...</p>
   {% endblock %}
   ```

如果你已经在 `cube_mage_pdf_js.yaml` 中配置了 `default_save_route`，调用可以更简单：

   ```twig
   {{ pdf_viewer(asset('documents/contract.pdf')) }}
   ```

## 🤝 贡献

欢迎任何形式的贡献！请阅读 `CONTRIBUTING.md` 文件了解如何开始。

## 📜 许可证

本项目基于 MIT 许可证开源。详情请见 [LICENSE](LICENSE) 文件。
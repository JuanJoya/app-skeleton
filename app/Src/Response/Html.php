<?php

declare(strict_types=1);

namespace App\Src\Response;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Esta clase construye un Response a partir de un template html-php básico,
 * no hay protección contra ataques XSS Cross-site scripting.
 */
class Html
{
    private Response $response;
    private string $template;
    private array $params;
    private bool $withLayout;
    private string $layout = 'layout';
    private string $templateFolder = ROOT_PATH . '/resources/views/';

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Compila un template y lo almacena en un objeto Response personalizado.
     * @param Response $response
     * @param string $template
     * @param array $params
     * @return Response
     */
    public static function response(Response $response, string $template, array $params = []): Response
    {
        $html = new self($response);
        return $html->make($template, $params);
    }

    /**
     * Compila un template.
     * @param string $template nombre del template.
     * @param array $params parámetros a enviar al template.
     * @param bool $withLayout
     * @return string
     */
    public function render(string $template, array $params = [], bool $withLayout = false): string
    {
        $this->template = normalizeName($template);
        $this->params = $params;
        $this->withLayout = $withLayout;
        return $this->loadContent();
    }

    /**
     * Construye un Response a partir de un template.
     * @param string $template nombre del template.
     * @param array $params parámetros a enviar al template.
     * @param int $status código de status http.
     * @param bool $withLayout
     * @return Response
     */
    public function make(string $template, array $params = [], bool $withLayout = false): Response
    {
        $this->response->getBody()->write(
            $this->render($template, $params, $withLayout)
        );
        return $this->response;
    }

    /**
     * @param string $layout nombre del layout.
     * @return void
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * @return string
     */
    private function getLayoutFileName(): string
    {
        return $this->templateFolder . $this->layout . '.php';
    }

    /**
     * @return string
     */
    private function getTemplateFileName(): string
    {
        return $this->templateFolder . $this->template . '.php';
    }

    /**
     * @return string contenido html.
     */
    private function loadContent(): string
    {
        if (($this->withLayout == true) && !file_exists($this->getLayoutFileName())) {
            throw new \RuntimeException("Layout file is missing.");
        } elseif (!file_exists($this->getTemplateFileName())) {
            throw new \RuntimeException("Template file for: [{$this->template}] is missing.");
        }
        return $this->includeTemplateFromFile();
    }

    /**
     * Se utiliza el output buffer para capturar el html "compilado" desde un template.
     * @return string contenido html.
     */
    private function includeTemplateFromFile(): string
    {
        extract($this->params);
        ob_start();
        if ($this->withLayout) {
            require $this->getTemplateFileName();
            $template_content = ob_get_contents();
            ob_clean();
            require $this->getLayoutFileName();
        } else {
            require $this->getTemplateFileName();
        }
        return ob_get_clean();
    }
}

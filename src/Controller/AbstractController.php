<?php


namespace App\Controller;


use Exception;

abstract class AbstractController
{
    /**
     * @param string $path
     */
    protected function redirect(string $path): void
    {
        header("Location: $path");

        exit;
    }

    /**
     * @param string $template_name
     * @param array $data
     */
    protected function show(string $template_name, array $data = []): void
    {
        require_once(__DIR__ . "/../View/$template_name.tpl");

        exit;
    }

    /**
     * @param Exception|null $error
     * @param array $data
     */
    protected function json(?Exception $error = null, array $data = []): void
    {
        if ($error !== null) {
            $message = $error->getMessage();
            $data['error'] = $message;
            header('HTTP/1.0 ' . $error->getCode() . " $message");
        }

        header('Content-Type: application/json');

        echo json_encode($data);

        exit;
    }
}
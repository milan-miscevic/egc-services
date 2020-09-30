<?php

declare(strict_types=1);

namespace EgcServices\Base\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class BaseAction
{
    /**
     * @param array<string, string> $args
     */
    abstract public function __invoke(Request $request, Response $response, array $args): Response;

    /**
     * @param mixed[] $data
     */
    protected function sendJson(Response $response, array $data, int $status = 200): Response
    {
        $payload = json_encode($data);

        if ($payload === false) {
            $payload = '{"message": "Server error"}';
            $status = 500;
        }

        $response->getBody()->write($payload);

        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param array<string, mixed> $data
     * @psalm-suppress MixedInferredReturnType
     */
    protected function sendHtml(Request $request, Response $response, string $template, array $data = [], int $status = 200): Response
    {
        /** @var string $scriptFilename */
        $scriptFilename = $request->getServerParams()['SCRIPT_FILENAME'];
        $templateFile = dirname($scriptFilename) . '/../view/' . $template . '.htm';

        ob_start();
        extract($data);
        /** @psalm-suppress UnresolvableInclude */
        require $templateFile;
        $content = ob_get_contents();
        ob_end_clean();

        if ($content === false) {
            $content = '';
        }

        $response->getBody()->write($content);

        /** @psalm-suppress MixedReturnStatement */
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'text/html');
    }
}

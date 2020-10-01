<?php

declare(strict_types=1);

namespace EgcServices\Home\Action;

use EgcServices\Base\Action\BaseAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Home extends BaseAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->sendHtml($request, $response, 'home');
    }
}

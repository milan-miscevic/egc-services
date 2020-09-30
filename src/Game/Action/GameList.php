<?php

declare(strict_types=1);

namespace EgcServices\Game\Action;

use EgcServices\Base\Action\BaseAction;
use EgcServices\Game\Domain\GameService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GameList extends BaseAction
{
    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $games = $this->gameService->getAll();

        return $this->sendJson($response, $games);
    }
}

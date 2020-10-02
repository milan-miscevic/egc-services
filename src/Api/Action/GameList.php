<?php

declare(strict_types=1);

namespace EgcServices\Api\Action;

use EgcServices\Army\Domain\ArmyService;
use EgcServices\Base\Action\BaseAction;
use EgcServices\Game\Domain\GameService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GameList extends BaseAction
{
    private GameService $gameService;
    private ArmyService $armyService;

    public function __construct(GameService $gameService, ArmyService $armyService)
    {
        $this->gameService = $gameService;
        $this->armyService = $armyService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $armies = $this->armyService->getAll();
        $units = [];

        foreach ($armies as $army) {
            $units[$army->getGameId()][] = $army->getName();
        }

        $games = $this->gameService->getAll();
        $data = [];

        foreach ($games as $game) {
            $data[] = [
                'id' => $game->getId(),
                'name' => $game->getName(),
                'status' => $game->getStatus(),
                'armies' => implode(', ', $units[$game->getId()] ?? []),
            ];
        }

        return $this->sendJson($response, $data);
    }
}

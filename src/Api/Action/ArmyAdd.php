<?php

declare(strict_types=1);

namespace EgcServices\Api\Action;

use EgcServices\Army\Domain\ArmyService;
use EgcServices\Base\Action\BaseAction;
use EgcServices\Base\Domain\Exception\InvalidData;
use EgcServices\Game\Domain\GameService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ArmyAdd extends BaseAction
{
    private ArmyService $armyService;
    private GameService $gameService;

    public function __construct(ArmyService $armyService, GameService $gameService)
    {
        $this->armyService = $armyService;
        $this->gameService = $gameService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $content = $request->getBody()->getContents();
        /** @var mixed $data */
        $data = json_decode($content, true);

        if (!isset($data['gameid'])) {
            return $this->sendJson($response, ['message' => 'Invalid Game ID']);
        }

        /** @psalm-suppress MixedArrayAccess */
        $gameId = (int) $data['gameid'];

        try {
            $id = $this->armyService->add($data);
        } catch (InvalidData $ex) {
            return $this->sendJson($response, ['errors' => $ex->getErrors()], 400);
        }

        $game = $this->gameService->getById($gameId);
        $game->setNext($id);
        $this->gameService->update($game);

        return $this->sendJson($response, ['id' => $id], 200);
    }
}

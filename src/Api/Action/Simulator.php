<?php

declare(strict_types=1);

namespace EgcServices\Api\Action;

use EgcServices\Base\Action\BaseAction;
use EgcServices\Simulator\Domain\Exception\GameFinished;
use EgcServices\Simulator\Domain\Exception\GameNotFound;
use EgcServices\Simulator\Domain\Exception\NotEnoughArmies;
use EgcServices\Simulator\Domain\SimulatorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Simulator extends BaseAction
{
    private SimulatorService $simulatorService;

    public function __construct(SimulatorService $simulatorService)
    {
        $this->simulatorService = $simulatorService;
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
            $result = $this->simulatorService->runRound($gameId);

            if ($result->getStatus()) {
                $message = sprintf(
                    "%s attacked %s chosen as %s with damage %s",
                    $result->getAttacker(),
                    $result->getDefender(),
                    $result->getStrategy(),
                    $result->getDamage()
                );
            } else {
                $message = sprintf(
                    "%s didn't attack %s",
                    $result->getAttacker(),
                    $result->getDefender()
                );
            }
        } catch (GameNotFound $ex) {
            $message = 'Invalid game';
        } catch (GameFinished $ex) {
            $message = 'Finished game';
        } catch (NotEnoughArmies $ex) {
            $message = 'Not enough armies. Please, add more.';
        }

        return $this->sendJson($response, ['message' => $message]);
    }
}

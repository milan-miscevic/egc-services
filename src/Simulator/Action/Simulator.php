<?php

declare(strict_types=1);

namespace EgcServices\Simulator\Action;

use EgcServices\Base\Action\BaseAction;
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
        $rand = $this->simulatorService->runRound();

        return $this->sendJson($response, ['rand' => $rand], 200);
    }
}

<?php

declare(strict_types=1);

namespace EgcServices\Api\Action;

use EgcServices\Army\Domain\ArmyService;
use EgcServices\Base\Action\BaseAction;
use EgcServices\Base\Domain\Exception\InvalidData;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ArmyAdd extends BaseAction
{
    private ArmyService $armyService;

    public function __construct(ArmyService $armyService)
    {
        $this->armyService = $armyService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $content = $request->getBody()->getContents();
        /** @var mixed $data */
        $data = json_decode($content, true);

        try {
            $id = $this->armyService->add($data);
        } catch (InvalidData $ex) {
            return $this->sendJson($response, ['errors' => $ex->getErrors()], 400);
        }

        return $this->sendJson($response, ['id' => $id], 200);
    }
}

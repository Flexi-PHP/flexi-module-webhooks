<?php

declare(strict_types=1);

namespace Flexi\Modules\WebHooks\Infrastructure\Controllers;

use Flexi\Contracts\Classes\HttpHandler;
use Flexi\Contracts\Interfaces\EventBusInterface;
use Flexi\Modules\WebHooks\Domain\Events\WebhookEvent;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class WebHookController extends HttpHandler
{
    private EventBusInterface $event_bus;

    public function __construct(ResponseFactoryInterface $response_factory, EventBusInterface $event_bus)
    {
        parent::__construct($response_factory);
        $this->event_bus = $event_bus;
    }

    protected function process(ServerRequestInterface $request): ResponseInterface
    {
        try {
            /** @var \stdClass $payload */
            $payload = $request->getAttribute('payload');
            $this->event_bus->dispatch(new WebhookEvent($payload->event, $payload->fired_by, (array) ($payload->data ?? null)));
        } catch (\Exception $e) {
            return $this->createResponse(400, $e->getMessage());
        }

        return $this->createResponse();
    }
}

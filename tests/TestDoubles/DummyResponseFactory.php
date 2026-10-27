<?php

declare(strict_types=1);

namespace CubaDevOps\Flexi\Modules\WebHooks\Tests\TestDoubles;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class DummyResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response($code, [], null, '1.1', $reasonPhrase);
    }
}

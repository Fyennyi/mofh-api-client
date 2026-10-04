<?php

declare(strict_types=1);

namespace Tests\Unit;

use Fyennyi\MofhApi\Client;
use Fyennyi\MofhApi\Connection;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as PsrHttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class ClientTest extends TestCase
{
    public function testGettersReturnRepositories() : void
    {
        $connection = new Connection('testuser', 'testpass');
        $httpClient = $this->createMock(PsrHttpClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);

        $client = new Client($connection, $httpClient, $requestFactory);

        $this->assertNotNull($client->getAccount());
        $this->assertNotNull($client->getDomain());
        $this->assertNotNull($client->getSupport());
        $this->assertNotNull($client->getSystem());
    }
}

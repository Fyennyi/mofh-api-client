<?php

declare(strict_types=1);

namespace Tests\Unit\Transport;

use Fyennyi\MofhApi\Connection;
use Fyennyi\MofhApi\Exception\MofhException;
use Fyennyi\MofhApi\Transport\HttpTransport;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\HttpFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Log\NullLogger;

class HttpTransportTest extends TestCase
{
    private Connection $connection;
    private HttpFactory $factory;
    private NullLogger $logger;

    protected function setUp() : void
    {
        $this->connection = new Connection('user', 'pass');
        $this->factory = new HttpFactory();
        $this->logger = new NullLogger();
    }

    public function testRequestGetJsonSuccess() : void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->willReturn(new Response(200, [], '{"result":[{"status":1}]}'));

        $transport = new HttpTransport($this->connection, $httpClient, $this->factory, $this->logger);
        $response = $transport->request('GET', 'test.php', [], 'json');

        $this->assertIsArray($response);
        $this->assertSame(1, $response['result'][0]['status']);
    }

    public function testRequestPostXmlSuccess() : void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->willReturn(new Response(200, [], '<root><result><status>1</status></result></root>'));

        $transport = new HttpTransport($this->connection, $httpClient, $this->factory, $this->logger);
        $response = $transport->request('POST', 'test.php', ['key' => 'val'], 'xml');

        $this->assertInstanceOf(\SimpleXMLElement::class, $response);
    }

    public function testRequestThrowsOnHttpException() : void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->willThrowException(new \Exception('Network error'));

        $transport = new HttpTransport($this->connection, $httpClient, $this->factory, $this->logger);
        
        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('Transport error: Network error');
        $transport->request('GET', 'test.php');
    }

    public function testParseResponseNull() : void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())->method('sendRequest')->willReturn(new Response(200, [], 'null'));

        $transport = new HttpTransport($this->connection, $httpClient, $this->factory, $this->logger);
        $this->assertNull($transport->request('GET', 'test.php'));
    }

    public function testParseResponseJsonError() : void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())->method('sendRequest')->willReturn(new Response(200, [], '{"result":[{"status":0,"statusmsg":"bad"}]}'));

        $transport = new HttpTransport($this->connection, $httpClient, $this->factory, $this->logger);
        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('API Error: bad');
        $transport->request('GET', 'test.php', [], 'json');
    }

    public function testParseResponseJsonFallbackToXml() : void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())->method('sendRequest')->willReturn(new Response(200, [], '<root><result><status>1</status></result></root>'));

        $transport = new HttpTransport($this->connection, $httpClient, $this->factory, $this->logger);
        $response = $transport->request('GET', 'test.php', [], 'json');
        $this->assertInstanceOf(\SimpleXMLElement::class, $response);
    }

    public function testParseResponseJsonInvalidFallback() : void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())->method('sendRequest')->willReturn(new Response(200, [], 'not json or xml'));

        $transport = new HttpTransport($this->connection, $httpClient, $this->factory, $this->logger);
        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('JSON Decode Error:');
        $transport->request('GET', 'test.php', [], 'json');
    }

    public function testParseResponseXmlError() : void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())->method('sendRequest')->willReturn(new Response(200, [], '<root><result><status>0</status><statusmsg>err</statusmsg></result></root>'));

        $transport = new HttpTransport($this->connection, $httpClient, $this->factory, $this->logger);
        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('API Error: err');
        $transport->request('GET', 'test.php', [], 'xml');
    }

    public function testParseResponseXmlInvalid() : void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())->method('sendRequest')->willReturn(new Response(200, [], 'not xml'));

        $transport = new HttpTransport($this->connection, $httpClient, $this->factory, $this->logger);
        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('XML Parse Error');
        $transport->request('GET', 'test.php', [], 'xml');
    }
}

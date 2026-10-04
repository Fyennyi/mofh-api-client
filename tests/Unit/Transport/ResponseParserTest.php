<?php

declare(strict_types=1);

namespace Tests\Unit\Transport;

use Fyennyi\MofhApi\Exception\MofhException;
use Fyennyi\MofhApi\Transport\ResponseParser;
use PHPUnit\Framework\TestCase;

class ResponseParserTest extends TestCase
{
    public function testParseJsonSuccess() : void
    {
        $data = ResponseParser::parse('{"key":"value"}', 'json');
        $this->assertSame(['key' => 'value'], $data);
    }

    public function testParseJsonThrowsOnInvalid() : void
    {
        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('Failed to decode JSON:');
        ResponseParser::parse('invalid json', 'json');
    }

    public function testParseJsonThrowsOnScalar() : void
    {
        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('JSON response is not an array');
        ResponseParser::parse('"scalar string"', 'json');
    }

    public function testParseXmlSuccess() : void
    {
        $data = ResponseParser::parse('<root><key>value</key></root>', 'xml');
        $this->assertInstanceOf(\SimpleXMLElement::class, $data);
        $this->assertSame('value', (string)$data->key);
    }

    public function testParseXmlThrowsOnInvalid() : void
    {
        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('Failed to parse XML response');
        ResponseParser::parse('invalid xml', 'xml');
    }

    public function testParseDefaultReturnsRawContent() : void
    {
        $data = ResponseParser::parse('raw text', 'text');
        $this->assertSame('raw text', $data);
    }
}

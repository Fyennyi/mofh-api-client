<?php

namespace Fyennyi\MofhApi\Contract;

use Fyennyi\MofhApi\Exception\MofhException;

interface TransportInterface
{
    /**
     * Sends a request to the API.
     *
     * @param string $method HTTP Method (GET, POST)
     * @param string $endpoint API Endpoint (e.g., 'createacct.php')
     * @param array $data Request payload
     * @param string $format Expected response format ('json', 'xml', 'text')
     * @return mixed Parsed response (array or object)
     * @throws MofhException
     */
    public function request(string $method, string $endpoint, array $data = [], string $format = 'json'): mixed;
}

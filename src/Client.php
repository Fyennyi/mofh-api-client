<?php

namespace Fyennyi\MofhApi;

use Fyennyi\MofhApi\Contract\Repository\AccountRepositoryInterface;
use Fyennyi\MofhApi\Contract\Repository\DomainRepositoryInterface;
use Fyennyi\MofhApi\Contract\Repository\SupportRepositoryInterface;
use Fyennyi\MofhApi\Contract\Repository\SystemRepositoryInterface;
use Fyennyi\MofhApi\Repository\AccountRepository;
use Fyennyi\MofhApi\Repository\DomainRepository;
use Fyennyi\MofhApi\Repository\SupportRepository;
use Fyennyi\MofhApi\Repository\SystemRepository;
use Fyennyi\MofhApi\Transport\HttpTransport;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Client
{
    public readonly AccountRepositoryInterface $account;
    public readonly DomainRepositoryInterface $domain;
    public readonly SupportRepositoryInterface $support;
    public readonly SystemRepositoryInterface $system;

    public function __construct(
        Connection $connection,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        LoggerInterface $logger = new NullLogger()
    ) {
        $transport = new HttpTransport($connection, $httpClient, $requestFactory, $logger);

        $this->account = new AccountRepository($transport);
        
        // Pass credentials explicitly where required by API oddities
        $this->domain = new DomainRepository(
            $transport, 
            $connection->getUsername(), 
            $connection->getPassword()
        );
        
        $this->support = new SupportRepository(
            $transport, 
            $connection->getUsername(), 
            $connection->getPassword()
        );
        
        $this->system = new SystemRepository($transport);
    }
}

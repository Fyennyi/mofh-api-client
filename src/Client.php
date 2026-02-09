<?php

namespace Fyennyi\MofhApi;

use Fyennyi\MofhApi\Contract\ClientInterface;
use Fyennyi\MofhApi\Contract\Repository\AccountRepositoryInterface;
use Fyennyi\MofhApi\Contract\Repository\DomainRepositoryInterface;
use Fyennyi\MofhApi\Contract\Repository\SupportRepositoryInterface;
use Fyennyi\MofhApi\Contract\Repository\SystemRepositoryInterface;
use Fyennyi\MofhApi\Repository\AccountRepository;
use Fyennyi\MofhApi\Repository\DomainRepository;
use Fyennyi\MofhApi\Repository\SupportRepository;
use Fyennyi\MofhApi\Repository\SystemRepository;
use Fyennyi\MofhApi\Transport\HttpTransport;
use Psr\Http\Client\ClientInterface as PsrHttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Client implements ClientInterface
{
    private AccountRepositoryInterface $account;
    private DomainRepositoryInterface $domain;
    private SupportRepositoryInterface $support;
    private SystemRepositoryInterface $system;

    public function __construct(
        Connection $connection,
        PsrHttpClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        LoggerInterface $logger = new NullLogger()
    ) {
        $transport = new HttpTransport($connection, $httpClient, $requestFactory, $logger);

        $this->account = new AccountRepository($transport);
        
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

    public function getAccount(): AccountRepositoryInterface
    {
        return $this->account;
    }

    public function getDomain(): DomainRepositoryInterface
    {
        return $this->domain;
    }

    public function getSupport(): SupportRepositoryInterface
    {
        return $this->support;
    }

    public function getSystem(): SystemRepositoryInterface
    {
        return $this->system;
    }
}

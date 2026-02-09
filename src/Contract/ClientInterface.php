<?php

namespace Fyennyi\MofhApi\Contract;

use Fyennyi\MofhApi\Contract\Repository\AccountRepositoryInterface;
use Fyennyi\MofhApi\Contract\Repository\DomainRepositoryInterface;
use Fyennyi\MofhApi\Contract\Repository\SupportRepositoryInterface;
use Fyennyi\MofhApi\Contract\Repository\SystemRepositoryInterface;

interface ClientInterface
{
    public function getAccount(): AccountRepositoryInterface;
    public function getDomain(): DomainRepositoryInterface;
    public function getSupport(): SupportRepositoryInterface;
    public function getSystem(): SystemRepositoryInterface;
}

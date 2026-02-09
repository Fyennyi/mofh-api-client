<?php

namespace Fyennyi\MofhApi\Contract\Repository;

use Fyennyi\MofhApi\DTO\Account\CreateAccountRequest;
use Fyennyi\MofhApi\DTO\Account\AccountResponse;

interface AccountRepositoryInterface
{
    public function create(CreateAccountRequest $request): AccountResponse;
    public function suspend(string $username, string $reason): bool;
    public function unsuspend(string $username): bool;
    public function remove(string $username): bool;
    public function changePassword(string $username, string $newPassword): bool;
    public function changePackage(string $username, string $package): bool;
}

<?php

namespace Fyennyi\MofhApi\Repository;

use Fyennyi\MofhApi\Contract\Repository\AccountRepositoryInterface;
use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\DTO\Account\CreateAccountRequest;
use Fyennyi\MofhApi\DTO\Account\AccountResponse;
use Fyennyi\MofhApi\Exception\MofhException;

final class AccountRepository implements AccountRepositoryInterface
{
    public function __construct(private TransportInterface $transport) {}

    public function create(CreateAccountRequest $request): AccountResponse
    {
        // Docs say createacct.php returns XML even on JSON API sometimes, 
        // forcing XML endpoint for consistency here.
        $response = $this->transport->request(
            'POST',
            'createacct.php',
            $request->toArray(),
            'xml'
        );

        return AccountResponse::fromXml($response);
    }

    public function suspend(string $username, string $reason): bool
    {
        $this->transport->request('POST', 'suspendacct.php', [
            'user' => $username,
            'reason' => $reason
        ], 'json');
        
        return true; // If no exception thrown by transport, it succeeded
    }

    public function unsuspend(string $username): bool
    {
        $this->transport->request('POST', 'unsuspendacct.php', ['user' => $username], 'json');
        return true;
    }

    public function remove(string $username): bool
    {
        $this->transport->request('POST', 'removeacct.php', ['user' => $username], 'json');
        return true;
    }

    public function changePassword(string $username, string $newPassword): bool
    {
        $this->transport->request('POST', 'passwd.php', [
            'user' => $username,
            'pass' => $newPassword
        ], 'json');
        return true;
    }

    public function changePackage(string $username, string $package): bool
    {
        // Note: Docs say changepackage returns XML even on JSON endpoint
        $this->transport->request('POST', 'changepackage.php', [
            'user' => $username,
            'pkg' => strtolower($package) // Requirement from docs
        ], 'xml');
        return true;
    }
}

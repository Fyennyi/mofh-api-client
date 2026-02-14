<?php

namespace Fyennyi\MofhApi\Repository;

use Fyennyi\MofhApi\Contract\Repository\DomainRepositoryInterface;
use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\Dto\Domain\UserDomain;

final class DomainRepository implements DomainRepositoryInterface
{
    /**
     * @param TransportInterface $transport
     * @param string $apiUser MOFH API Username (for legacy compatibility in some endpoints)
     * @param string $apiKey  MOFH API Key
     */
    public function __construct(
        private TransportInterface $transport,
        private string $apiUser,
        private string $apiKey
    ) {}

    /**
     * @inheritDoc
     */
    public function checkAvailability(string $domain): bool
    {
        $response = $this->transport->request('POST', 'checkavailable.php', [
            'api_user' => $this->apiUser,
            'api_key'  => $this->apiKey,
            'domain'   => $domain
        ], 'json');

        // MOFH returns '1' if available, '0' or error message if not
        return (string)($response[0] ?? $response) === '1';
    }

    /**
     * @inheritDoc
     * @return UserDomain[]
     */
    public function getUserDomains(string $username): array
    {
        // MOFH's getuserdomains.php is most reliable via XML
        $xml = $this->transport->request('POST', 'getuserdomains.php', [
            'api_user' => $this->apiUser,
            'api_key'  => $this->apiKey,
            'username' => $username
        ], 'xml');

        $domains = [];

        /**
         * MOFH XML structure for domains usually looks like:
         * <getuserdomains>
         * <result>
         * <item>domain1.com</item>
         * <item>domain2.com</item>
         * </result>
         * </getuserdomains>
         */
        if (isset($xml->result->item)) {
            foreach ($xml->result->item as $item) {
                $domains[] = new UserDomain(
                    domain: (string)$item,
                    username: $username
                );
            }
        }

        return $domains;
    }

    public function getUserByDomain(string $domain): ?array
    {
        $response = $this->transport->request('POST', 'getdomainuser.php', [
            'api_user' => $this->apiUser,
            'api_key'  => $this->apiKey,
            'domain'   => $domain
        ], 'json');

        return is_array($response) ? $response : null;
    }
}

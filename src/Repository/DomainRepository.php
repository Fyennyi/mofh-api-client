<?php

namespace Fyennyi\MofhApi\Repository;

use Fyennyi\MofhApi\Contract\Repository\DomainRepositoryInterface;
use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\Dto\Domain\UserDomain;

final class DomainRepository implements DomainRepositoryInterface
{
    /**
     * @param TransportInterface $transport
     * @param string             $apiUser   MOFH API Username (for legacy compatibility in some endpoints)
     * @param string             $apiKey    MOFH API Key
     */
    public function __construct(
        private TransportInterface $transport,
        private string $apiUser,
        private string $apiKey
    ) {}

    /**
     * @inheritDoc
     */
    public function checkAvailability(string $domain) : bool
    {
        $response = $this->transport->request('POST', 'checkavailable.php', [
            'api_user' => $this->apiUser,
            'api_key'  => $this->apiKey,
            'domain'   => $domain
        ], 'json');

        if (is_array($response)) {
            $value = $response[0] ?? '';
            return '1' === (is_scalar($value) ? (string)$value : '');
        }

        return '1' === (is_scalar($response) ? (string)$response : '');
    }

    /**
     * @inheritDoc
     *
     * @return array<int, UserDomain>
     */
    public function getUserDomains(string $username) : array
    {
        // MOFH's getuserdomains.php is most reliable via XML
        $xml = $this->transport->request('POST', 'getuserdomains.php', [
            'api_user' => $this->apiUser,
            'api_key'  => $this->apiKey,
            'username' => $username
        ], 'xml');
        
        if (! $xml instanceof \SimpleXMLElement) {
            throw new \Fyennyi\MofhApi\Exception\MofhException("Expected XML response from getuserdomains.php");
        }

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
        /** @var \SimpleXMLElement $result */
        $result = $xml->result;
         
        if (isset($result->item)) {
            foreach ($result->item as $item) {
                $domains[] = new UserDomain(
                    domain: (string)$item,
                    username: $username
                );
            }
        }

        return $domains;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getUserByDomain(string $domain) : ?array
    {
        $response = $this->transport->request('POST', 'getdomainuser.php', [
            'api_user' => $this->apiUser,
            'api_key'  => $this->apiKey,
            'domain'   => $domain
        ], 'json');

        if (is_array($response)) {
            /** @var array<string, mixed> $response */
            return $response;
        }

        return null;
    }
}

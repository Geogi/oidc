<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 1/30/19
 * Time: 7:31 PM
 */

namespace App\Security;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Security\Core\Security;

class ApiClient
{
    /** @var ClientInterface */
    private $httpClient;

    /** @var Security */
    private $security;

    public function __construct(
        Security $security
    )
    {
        $this->httpClient = new Client();
        $this->security = $security;
    }

    /**
     * @param string $url
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $url)
    {
        $tokenHeader = [];
        if ($user = $this->security->getUser()) {
            $tokenHeader = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $user->getUsername(),
                ],
            ];
        }
        $endpoint = getenv('API_ENDPOINT');
        return $this->httpClient->request('GET', $endpoint . $url, $tokenHeader);
    }
}

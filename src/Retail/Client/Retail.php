<?php
declare(strict_types=1);

namespace DealPerch\Retail\Client;

use DealPerch\Retail\Client\DTO\SearchResponse;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\AbstractProvider;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class Retail extends AbstractSSOEnabledAPIClient
{

    private $logger;

    public function __construct(AbstractProvider $authProvider, Client $HTTPClient, LoggerInterface $logger, Configuration $configuration)
    {
        $this->authProvider = $authProvider;
        $this->HTTPClient = $HTTPClient;
        $this->logger = $logger;
        $this->configuration = $configuration;

    }

    /**
     * @return SearchResponse
     *
     */
    public function search(string $GTIN): SearchResponse
    {
        $response = $this->HTTPClient->post($this->getConfiguration()->getRetailBaseURL() . 'search', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->getAuthorizationHeaderValue()
            ],
            'body' =>
                json_encode(['GTIN' => $GTIN], JSON_PRETTY_PRINT)
        ]);
        $this->logger->log(Logger::DEBUG, __METHOD__ . ' - ' . $response->getBody()->getContents());
        $response->getBody()->rewind();


        $responseAsArr = json_decode($response->getBody()->getContents(), true);

        $out = SearchResponse::fromArray($responseAsArr);

        return $out;

    }
}
<?php


namespace DealPerch\Test\Retail\Client;


use DealPerch\Retail\Client\Configuration;
use DealPerch\Retail\Client\Retail;
use DealPerch\Retail\Client\DTO\SearchResponse;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\GenericProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class RetailTest extends TestCase
{

    /**
     * @var Retail
     */
    private $APIClient;

    public function setUp()
    {

        $SSOBaseURL = 'http://localhost:8080/v1/';

        $authProvider = new GenericProvider([
            'clientId' => '374ddc0d-4e45-4517-8b6b-e82288508b4a',    // The client ID assigned to you by the provider
            'clientSecret' => '',   // The client password assigned to you by the provider
            'urlAuthorize' => $SSOBaseURL . 'auth',
            'urlAccessToken' => $SSOBaseURL . 'auth',
            'urlResourceOwnerDetails' => $SSOBaseURL . 'identity',
        ]);

        $HTTPClient = new Client();

        $logger = new Logger('RetailTest');
        try {
            $logger->pushHandler(new StreamHandler(__DIR__ . '/../../retail.log', Logger::DEBUG));
        } catch (\Exception $e) {
            // do nothing
        }

        $configuration = new Configuration(__DIR__ . '/../../credentials.cache', 'http://localhost:8082/v1/', $SSOBaseURL, 'password', 'test@dealperch.com', 'testing');

        $this->APIClient = new Retail($authProvider, $HTTPClient, $logger, $configuration);

        $this->assertInstanceOf(Retail::class, $this->APIClient);
    }


    public function testSearch()
    {

        $res = $this->APIClient->search('013132313092');

        $this->assertInstanceOf(SearchResponse::class, $res);
    }
}

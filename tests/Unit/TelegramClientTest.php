<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Clients\TelegramClient;
use App\Clients\BaseClient;
use GuzzleHttp\Client;

class TelegramClientTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->config = config('telegram');
        $this->client = new TelegramClient($this->config);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(BaseClient::class, $this->client);
    }

    public function testInstanceGuzzleClientWithoutMockHandler()
    {
        $this->config['use_mock_handler'] = false;
        $client = new TelegramClient($this->config);
        $guzzleClient = $client->getClient();
        $guzzleConfig = $guzzleClient->getConfig();
        $this->assertInstanceOf(Client::class, $guzzleClient);
        $this->assertEquals($this->config['client_config']['base_uri'], 
            $guzzleConfig['base_uri']->__toString()
        );
        $this->assertEquals($this->config['client_config']['http_errors'], 
            $guzzleConfig['http_errors']
        );
    }
}

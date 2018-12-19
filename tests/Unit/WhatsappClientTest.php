<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Clients\WhatsappClient;
use App\Clients\BaseClient;
use GuzzleHttp\Client;

class WhatsappClientTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->config = config('whatsapp');
        $this->client = new WhatsappClient($this->config);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(BaseClient::class, $this->client);
    }

    public function testInstanceGuzzleClientWithoutMockHandler()
    {
        $this->config['use_mock_handler'] = false;
        $client = new WhatsappClient($this->config);
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

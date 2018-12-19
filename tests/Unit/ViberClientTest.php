<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Clients\ViberClient;
use GuzzleHttp\Client;
use App\Clients\BaseClient;

class ViberClientTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->config = config('viber');
        $this->client = new ViberClient($this->config);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(BaseClient::class, $this->client);
    }

    public function testInstanceGuzzleClientWithoutMockHandler()
    {
        $this->config['use_mock_handler'] = false;
        $client = new ViberClient($this->config);
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

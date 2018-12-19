<?php

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class TelegramClient extends BaseClient
{
    protected $config;

    // TODO any custom specific logic
    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->initClient();
    }

    public function initClient(): void
    {
        if (empty($this->config['use_mock_handler'])) {
            $this->client = new Client(
                $this->config['client_config'] ?? []
            );
        } else {
            $mockStatusCode = $this->config['mock_status_code'];
            $this->client = $this->getMockClient([new Response($mockStatusCode)]);
        }
    }
}

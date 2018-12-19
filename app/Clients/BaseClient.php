<?php

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;

abstract class BaseClient
{
    protected $client;
    protected $info = [];
    protected $error;

    abstract public function initClient(): void;

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getMockClient(array $queue = []): Client
    {
        return new Client(
            [
                'handler' => new MockHandler($queue),
            ]
        );
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    public function request(Request $request): Response
    {
        $instance = $this;
        $response = $this->getClient()->send(
            $request,
            [
                'on_stats' => function (TransferStats $stats) use ($instance) {
                    $instance->info = $stats->getHandlerStats();

                    if (!$stats->hasResponse()) {
                        $instance->error = $stats->getHandlerErrorData();
                    }
                }
            ]
        );

        return $response;
    }
}

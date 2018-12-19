<?php

namespace App\Jobs;

use App\Clients\BaseClient;
use App\Clients\TelegramClient;
use App\Clients\ViberClient;
use App\Clients\WhatsappClient;
use App\JobHistory;
use App\Validators\InputRequest;
use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\Response;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $typeMessenger;
    protected $textMessage;
    public $tries = 3; // max attempts for processing jobs if job was failed
    protected const SUCCESSFUL_STATUS = 'success';
    protected const FAILED_STATUS = 'failed';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $userId, string $typeMessenger, string $textMessage)
    {
        $this->userId = $userId;
        $this->typeMessenger = $typeMessenger;
        $this->textMessage = $textMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = $this->getClient($this->typeMessenger);
        /** @var \GuzzleHttp\Psr7\Response $response */
        $response = $client->request(
            new Request(
                'POST',
                '/api/mock',
                [
                    'X-Token' => md5(time()),
                ]
            )
        );
        $status = $response->getStatusCode() === Response::HTTP_OK ? self::SUCCESSFUL_STATUS : self::FAILED_STATUS;
        $timeExecute = $client->getInfo()['total_time'] ?? 0;
        $model = new JobHistory();
        $model->fill(
            [
                'user_id' => $this->userId,
                'type_messenger' => $this->typeMessenger,
                'time_execute' => $timeExecute,
                'status' => $status,
                'job_id' => $this->job->getJobId(),
            ]
        );
        $model->save();

        if ($status === self::FAILED_STATUS) {
            $config = $this->getConfig($this->typeMessenger);
            $timeout = (int) array_get($config, 'release_timeout', 1);
            $this->release($timeout);
            echo sprintf('Job was released into queue');
        }
        echo sprintf('User id: %s, status_code: %s', $this->userId, $response->getStatusCode()).PHP_EOL;
    }


    protected function getClient(string $type): BaseClient
    {
        switch ($type) {
            case InputRequest::TELEGRAM_TYPE_MESSAGE:
                return new TelegramClient(config('telegram', []));
                break;
            case InputRequest::VIBER_TYPE_MESSAGE:
                return new ViberClient(config('viber', []));
                break;
            case InputRequest::WHATSAPP_TYPE_MESSAGE:
                return new WhatsappClient(config('whatsapp', []));
                break;
            default:
                throw new \InvalidArgumentException('Invalid type of messenger');
        }
    }

    protected function getConfig(string $type): array
    {
        return config($type, []);
    }
}

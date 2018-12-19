<?php

namespace App\Processors;

use App\Jobs\SendMessage;
use Illuminate\Support\Facades\Queue;

class MessageProcessor
{
    /**
     * Process message data
     *
     * @param array $messageData
     * @return array
     */
    public function process(array $messageData): array
    {
        $result = [];
        $users = array_get($messageData, 'users');
        $textMessage = array_get($messageData, 'text');

        foreach ($users as $user) {
            $userId = array_get($user, 'user_id');
            $typeMessenger = array_get($user, 'type');
            $timeout = array_get($user, 'timeout');
            $job = new SendMessage($userId, $typeMessenger, $textMessage);
            $jobId = Queue::laterOn('send', $timeout, $job);
            $result[] = $jobId;
        }
        
        return $result;
    }
}

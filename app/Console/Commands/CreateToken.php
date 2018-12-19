<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Laravel\Passport\Bridge\Client;
use Laravel\Passport\ClientRepository;
use League\OAuth2\Server\AuthorizationServer;
use Symfony\Component\HttpFoundation\Response;

class CreateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:token {client_id} {user_id} {user_password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = (int) $this->argument('client_id');
        $userId = (int) $this->argument('user_id');
        $userPassword = $this->argument('user_password');
        $auth = app()->make(AuthorizationServer::class);
        $repository = new ClientRepository();
        $client = $repository->findActive($id);
        $user = \App\User::find($userId);
        $data = [
            'client_id' => $id,
            'client_secret' => $client->secret,
            'grant_type' => 'password',
            'username' => $user->email,
            'password' => $userPassword, //'fy57BqhLb57TJLE',
            'scope' => '',
        ];
        $request = Request::create('oauth/token', 'POST', $data, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);
        $response = app()->handle($request);

        if ($response->getStatusCode() === Response::HTTP_OK) {
            $this->output->writeln(sprintf('Token payload: %s', $response->getContent()));
        }
    }
}

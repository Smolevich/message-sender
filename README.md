# Message Sender

## Table of contents

- [Installation](#installation)
  - [Using docker compose](#using-docker-compose)
- [Api](#api)
  - [Tokens](#tokens)
  - [Endpoints](#endpoints)
- [Workers](#workers)  
- [Tests](#tests)  

## Installation

### Using docker compose

1. Run `docker-compose up -d` in `project_dir`
2. Set credentils for database in env file
3. Install dependencies `docker-compose exec php composer install`
4. Apply migrations `docker-compose exec php php artisan migrate`

## Api

### Tokens

1. Go to endpoint `/register` and register
2. Remember our password from step one
3. Run command `php artisan passport:client --password`. In output of this command you see `client_id` and `client_secret`
4. Create request data for getting of token

```php
$http = new GuzzleHttp\Client;

$response = $http->post('http://your-app.com/oauth/token', [
    'form_params' => [
        'grant_type' => 'password',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'username' => 'taylor@laravel.com',
        'password' => 'my-password',
        'scope' => '',
    ],
]);
```

You can use console command `php artisan create:token {client_id} {user_id} {user_password}` or send request by himself

Save received token data

## Endpoints

All requests must http header `Authorization: Bearer <access_token>`

* `POST /api/messages`

|   Attribute    |  Type     |  Required     | Description      |
|  ---  |  ---  |  ---  |  ---  |
|  users[]     |   array    |   yes    |    Users array   |
|  text     |   string    | yes      |   Text message, max length 240 characters    |

|  Attribute `users[]`     |  Type     |   Required    |   Description    |
|  ---  |  ---  |  ---  |  ---  |
| user_id     |    string   |    yes   |  User_id in messenger     |
|   type    |   string    |   yes    |   Type of messenger. Available types: telegram, viber, whatsapp    |
|   timeout    |   integer    |   yes    |   Timeout through which will send message    |


Example of response:

```json
{
    "data": [
        "KQvPNm0f7s37a4GnptpzVADdPdGWhE55"
    ]
}
```

* `GET /api/history/{job_id}`

Example of response:

```json
{
    "data": [
        {
            "id": 15,
            "job_id": "bvbxXHcN7h7Ip7D4RohAdbWXbWfq2XnQ",
            "user_id": "5",
            "type_messenger": "viber",
            "status": "failed",
            "created_at": "2018-12-18 12:34:11",
            "updated_at": "2018-12-18 12:34:11"
        },
        {
            "id": 16,
            "job_id": "bvbxXHcN7h7Ip7D4RohAdbWXbWfq2XnQ",
            "user_id": "5",
            "type_messenger": "viber",
            "status": "failed",
            "created_at": "2018-12-18 12:34:11",
            "updated_at": "2018-12-18 12:34:11"
        },
        {
            "id": 17,
            "job_id": "bvbxXHcN7h7Ip7D4RohAdbWXbWfq2XnQ",
            "user_id": "5",
            "type_messenger": "viber",
            "status": "failed",
            "created_at": "2018-12-18 12:34:11",
            "updated_at": "2018-12-18 12:34:11"
        }
    ]
}
```

## Tests

For tests run command `composer run-test`

## Workers

After send request on endpoint `/api/messages` jobs was pushed into queue
and worker take job from queue

`Job payload`:

 * `type_messenger` - one of `viber,telegram,whatsapp`
 * `user_id` - user identifier
 * `text message` - text message for user in one of messenger

 Max attempt to execute job setted in property of class `App\Jobs\SendMessage` - 3 times.
 If max attempts was exceeded - job gets in failed_jobs. After try to send in api of messenger created `job history` record




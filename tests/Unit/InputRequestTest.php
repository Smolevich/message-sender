<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Validators\InputRequest;

class InputRequestTest extends TestCase
{
    protected $validator;

    public function setUp() 
    {
        parent::setUp();
        $this->validator = new InputRequest();
    }

    /**
     *
     * @dataProvider inputDataProvider
     */
    public function testValidate($data, $expectedErrors, $expected)
    {
        $actual = $this->validator->validate($data);
        $actualErrors = $this->validator->getErrors();
        $this->assertEquals($expectedErrors, $actualErrors);
        $this->assertEquals($expected, $this->validator->validate($data));   
    }

    public function inputDataProvider()
    {
        return [
            // Incorrect users field
            [
                [
                    'text' => 'test_message'
                ], 
                [
                    'users' => [
                        'The users field is required.'
                    ]
                ],
                false
            ],
            // Length of text fields greater than 240 characters
            [
                [
                    'users' => [
                        [
                            'user_id' => '3',
                            'type' => 'viber',
                            'timeout' => 2
                        ]
                    ],
                    'text' => str_repeat('s', 241)
                ],
                [
                    'text' => [
                        'The text may not be greater than 240 characters.'
                    ]
                ],
                false
            ],
            // Timeout out of range [1,300]
            [
                [
                    'users' => [
                        [
                            'user_id' => '2',
                            'type' => 'telegram',
                            'timeout' => 301
                        ]
                    ],
                    'text' => 'test'
                ],
                [
                    'users.0.timeout' => [
                        'The users.0.timeout may not be greater than 300.'
                    ]
                ],
                false
            ],
            // Incorrect users elements
            [
                [
                    'users' => [
                        [
                            'user_id' => 13,
                            'type' => 'telegram',
                            'timeout' => 98
                        ],
                        [
                            'user_id' => '16',
                            'type' => 'undefinded',
                            'timeout' => 90
                        ]
                    ],
                    'text' => 'test'
                ],
                [
                    'users.0.user_id' => [
                        'The users.0.user_id must be a string.'
                    ],
                    'users.1.type' => [
                        'Invalid type of messenger. Available values: telegram,whatsapp,viber'
                    ]
                ],
                false    
            ]
        ];
    }
}

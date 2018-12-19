<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobHistory extends Model
{
    public $table = 'job_history';
    
    public $fillable = [
        'status',
        'time_execute',
        'user_id',
        'job_id',
        'type_messenger'
    ];
}

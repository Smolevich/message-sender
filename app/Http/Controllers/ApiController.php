<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobHistoryResource;
use App\JobHistory;
use App\Processors\MessageProcessor;
use App\Validators\InputRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * Create message and process him
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $result = [];
        $data = $request->post();
        $text = array_get($data, 'text');
        $userIds = array_get($data, 'user_ids');
        $validator = new InputRequest();
        
        if (!$validator->validate($data)) {
            $result['errors'] = $validator->getErrors();
        } else {
            $result['data'] = (new MessageProcessor())->process($data);
        }

        return $result;
    }

    public function history(Request $request, $jobId)
    {
        return JobHistoryResource::collection(
            JobHistory::where('job_id', $jobId)->get()
        );
    }
}

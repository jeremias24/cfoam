<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use App\Models\UserRequestApproval;
use App\Services\Service;

use App\Http\Controllers\API\V1\User\UserRequestsController;

class VerifyHelper
{
    protected $request;
    protected $userRequestsController;
    protected $status;

    public function __construct(userRequestsController $userRequestsController, Service $service)
    {
        $this->service = $service;
        $this->userRequestsController = $userRequestsController;
        $this->status = null;
    }

    public function process($request)
    {

        $this->request = $request;

        return $this;

    }

    public function updateVerification($model, $request, $filterId, $alias)
    {
        $request = (object) $request;
        $data = $this->service
            ->model($model)
            ->query((object) [
                'show' => 'filter',
                'filter' => [
                    $alias . '.id' => $request->{$filterId},
                ],
            ])
            ->result()
            ->first();
        
        if ($data->status_code == "SBMTD") {

            $serviceResponse = $this->service
                ->model($model
                ->where(['id' => $request->userRequestId])
                ->update([
                    'status_id' => $request->statusId,
                    'status_code' => $request->statusCode,
                    'email_verified_flag' => 1,
                    'email_verified_datetime' => now()->toDateTimeString(),
                ]));

            $this->status = 'Verified';
        } else if ($data->status_code == "VRFD") 
            $this->status = 'Already Verified';
         else 
            $this->status = 'Something Went Wrong';
        

        return $this;
    }

    public function result()
    {
        return (object) [
            'status' => $this->status,
            'request' => $this->request,
        ];
    }

}
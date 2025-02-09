<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use App\Http\Controllers\API\V1\User\UserSystemAccessRequestsController as SystemAccessesRequest;

class AccessService
{
    protected $systemAccessesRequest;

    public function __construct(SystemAccessesRequest $systemAccessesRequest)
    {
        $this->systemAccessesRequest = $systemAccessesRequest;
    }

    public function createAccess(array $accesses, int $userRequestId) : bool 
    {
        try {
            foreach ($accesses as $key => $access) {
                $accessWithRequestId = Arr::add($access, Str::camel('user_request_id'), $userRequestId);
                $request = new Request($accessWithRequestId);
                $response = json_decode($this->systemAccessesRequest->store($request)->getContent(), true);
            }
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
}
?>
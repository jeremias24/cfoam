<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use App\Http\Controllers\API\V1\User\UserRequestApprovalsController as RequestApprovals;

class ApproverService
{
    protected $requestApprovals;

    public function __construct(RequestApprovals $requestApprovals)
    {
        $this->requestApprovals = $requestApprovals;
    }

    public function createApprover(array $approvers, int $userRequestId) : bool 
    {
        try {
            foreach ($approvers as $key => $approver) {
                $approversWithRequestId = Arr::add($approver, Str::camel('user_request_id'), $userRequestId);
                $request = new Request($approversWithRequestId);
                $response = json_decode($this->requestApprovals->store($request)->getContent(), true);
            }
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
}
?>
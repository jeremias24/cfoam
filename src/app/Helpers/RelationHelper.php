<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use App\Services\Service;
use App\Helpers\Helper;

class RelationHelper
{

    protected $service;
    protected $helper;

    protected $data;
    protected $error = false;

    public function __construct(Service $service, Helper $helper)
    {

        $this->service = $service;
        $this->helper = $helper;

    }

    public function updateUserRequest($userRequest, $condition)
    {


        try {
            if (!$this->error and $condition) {

                $serviceResponse = $this->service
                    ->controller('App\Http\Controllers\API\V1\User\UserRequestsController')
                    ->update($userRequest)
                    ->result();

                if ($serviceResponse->status == 'Failed') {
                    throw new \Exception('Updating of user request has failed');
                }
            }
        } catch (\Exception $error) {
            $this->error = true;

            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }
        
        return $this;
    }

    public function insertApproval($approvals, $userRequestId)
    {

        try {
            if (!$this->error and !is_null($approvals) and count($approvals)) {
                foreach ($approvals as $approval) {
                    $serviceResponse = $this->service
                        ->controller('App\Http\Controllers\API\V1\User\UserRequestApprovalsController')
                        ->insert(Arr::add($approval, Str::camel('user_request_id'), $userRequestId))
                        ->result();

                    if ($serviceResponse->status == 'Failed') {
                        throw new \Exception('Saving of approvals has failed');
                    }
                }
            }
        } catch (\Exception $error) {
            $this->error = true;

            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

        return $this;

    }

    public function insertRejectedApproval($rejectedApproval, $condition)
    {

        try {
            if (!$this->error and $condition) {
                $serviceResponse = $this->service
                    ->controller('App\Http\Controllers\API\V1\RejectedApprovalsController')
                    ->insert($rejectedApproval)
                    ->result();

                if ($serviceResponse->status == 'Failed') {
                    throw new \Exception('Saving of rejected approvals has failed');
                }
            }
        } catch (\Exception $error) {
            $this->error = true;

            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

        return $this;
    }

    public function insertUserRequestLog($userRequestLogs)
    {
        try {
            if (!$this->error and !is_null($userRequestLogs)) {
                $serviceResponse = $this->service
                    ->controller('App\Http\Controllers\API\V1\LogsController')
                    ->insert($userRequestLogs)
                    ->result();
                
                if ($serviceResponse->status == 'Failed') {
                    throw new \Exception('Saving of user request logs has failed');
                }
            }
        } catch (\Exception $error) {
            $this->error = true;

            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

        return $this;

    }

    public function insertPersonalInformations($userRequest, $condition) {

        try {
            if (!$this->error and !is_null($userRequest) and $condition) {

                $serviceResponse = $this->service
                    ->controller('App\Http\Controllers\API\V1\PortalPersonalInformationsController')
                    ->insert($userRequest)
                    ->result();
            
                if ($serviceResponse->status == 'Failed') {
                    throw new \Exception('Saving of user personal informations has failed');
                }
            }
        } catch (\Exception $error) {
            $this->error = true;

            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

        return $this;
    }

    public function insertSystemAccesses($accesses, $condition) {

        try {
            if (!$this->error and !is_null($accesses) and $condition) {
      
                foreach($accesses as $access) {
          
                    $serviceResponse = $this->service
                        ->controller('App\Http\Controllers\API\V1\User\UserSystemAccessesController')
                        ->insert($access)
                        ->result();
                
                    if ($serviceResponse->status == 'Failed') {
                        throw new \Exception('Saving of user access has failed');
                    }
                }
            }
        } catch (\Exception $error) {
            $this->error = true;

            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

        return $this;
    }

    public function insertUser($user, $condition)
    {
        try {
            if (!$this->error and $condition) {
                $serviceResponse = $this->service
                    ->controller('App\Http\Controllers\API\V1\User\UsersController')
                    ->insert($user)
                    ->result();
                
                if ($serviceResponse->status == 'Failed') {
                    throw new \Exception('Saving of user has failed');
                }
            }
        } catch (\Exception $error) {
            $this->error = true;

            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

        return $this;
    }

    public function uploadAttachment($attachments, $referenceId)
    {

        try {
            if (!$this->error and !is_null($attachments) and count($attachments)) {
                $serviceResponse = $this->service
                    ->controller('App\Http\Controllers\API\V1\AttachmentsController')
                    ->uploadFile($attachments, $referenceId)
                    ->result();

                if ($serviceResponse->status == 'Failed') {
                    throw new \Exception('Saving of attachments has failed');
                }
            }
        } catch (\Exception $error) {
            $this->error = true;

            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

        return $this;

    }

    public function status()
    {

        return $this->error ? $this->data : (object) [
            'status' => 'Passed',
        ];

    }

}
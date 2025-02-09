<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Services\Service;
use App\Helpers\Helper;
use App\Helpers\ApprovalsHelper;

class MailHelper
{
    protected $service;
    protected $helper;
    protected $approvalsHelper;

    public function __construct(Service $service, Helper $helper, ApprovalsHelper $approvalsHelper)
    {
        $this->service = $service;
        $this->helper = $helper;
        $this->approvalsHelper = $approvalsHelper;
    }

    public function sendApprovalNotification($request, $approval, $approvalProcess, $account, $email = null)
    {
        if (!is_null($approvalProcess)) {
            if (Str::contains($approvalProcess->status, ['Approved', 'Rejected', 'Completed'])) {                
                $this->service
                ->mail((object) [
                    'sender' => config('app.mail_username'),
                    'subject' => 'CRGMPC Central Access Approval',
                    'template' => 'mails.approval_notification',
                    'to' => $email,
                    'data' => (object) [
                        'notification_type' => $approvalProcess->status,
                        'first_name' => $approval->approver_first_name,
                        'last_name' => $approval->approver_last_name,
                        'username' => $account->username,
                        'password' => $account->password,
                        'reference_id' => $request->referenceId,
                        'reason' => $request->has('reason') ? $request->input('reason') : null,
                    ],
                ]);
            }
        }
    }

    public function sendApprovalRequest($model, $data, $approver, $accesses)
    {
        $userRequest = $this->service
            ->model($model)
            ->query((object) [
                'show' => 'filter',
                'filter' => [
                    'ur.reference_id' => $data->reference_id,
                ],
            ])
            ->relation(\App\Models\UserRequestApproval::class, 'user_request_approvals', 'user_request_id', 'id')
            ->relation(\App\Models\UserSystemAccessRequest::class, 'user_system_access_requests', 'user_request_id', 'id')
            ->result()
            ->first();

        $approvalDetails = $this->approvalsHelper->approvalDetails($userRequest->user_request_approvals);

        if (($approvalDetails->first->status_code == 'PNDG' and $approvalDetails->current->status_code == 'PNDG') and
            ($approvalDetails->first->hierarchy == $approvalDetails->current->hierarchy)) {
            
                $this->service
                ->mail((object) [
                    'sender' => config('app.mail_username'),
                    'subject' => 'CRGMPC Central Access Approval',
                    'template' => 'mails.approval_request',
                    'to' => $approver->approver_email,
                    'data' => (object) [
                        'option' => $this->helper
                            ->toApprovalButton(['confirm', 'reject'])
                            ->encrypt([
                                Str::camel('user_request_id') => $userRequest->id,
                                Str::camel('reference_id') => $userRequest->reference_id,
                                Str::camel('approval_id') => $approvalDetails->current->id,
                                'hierarchy' => $approvalDetails->current->hierarchy,
                                Str::camel('approver_id') => $approver->approver_employee_no,
                            ])
                            ->result(),
                        'id' => $userRequest->id,
                        'user' => Str::title($userRequest->first_name . ' ' . $userRequest->last_name),
                        'author_employee_id' => $userRequest->author_employee_id,
                        'author_department' => $userRequest->author_department,
                        'author_section' => $userRequest->author_section,
                        'author_dealer_id' => $userRequest->author_dealer_id,
                        'author_dealer' => $userRequest->author_dealer,
                        'author_dealer_satellite' => $userRequest->author_dealer_satellite,
                        'author_organization_id' => $userRequest->author_organization_id,
                        'author_organization' => $userRequest->author_organization,
                        'author' => Str::title($userRequest->author_first_name . ' ' . $userRequest->author_last_name),
                        'approver' => Str::title($approver->approver_first_name . ' ' . $approver->approver_last_name),
                        'user_request' => Str::title($userRequest->first_name . ' ' . $userRequest->last_name),
                        'reference_id' => $userRequest->reference_id,
                        'created_date' => date('m/d/Y', strtotime($userRequest->created_datetime)),
                        'accesses' => $accesses
                    ],
                ]);
        }

        return $userRequest;

    }

    public function sendVerification($model, $data) {

        $userRequest = $this->service
            ->model($model)
            ->query((object) [
                'show' => 'filter',
                'filter' => [
                    'ur.id' => $data->id,
                ],
            ])
            ->relation(\App\Models\UserRequestApproval::class, Str::camel('user_request_approvals'), 'user_request_id', 'id')
            ->relation(\App\Models\UserSystemAccessRequest::class, Str::camel('user_system_access_requests'), 'user_request_id', 'id')
            ->result()
            ->first();

        if ($userRequest->status_code == 'SBMTD') {
            $this->service
                ->mail((object) [
                    'sender' => config('app.mail_username'),
                    'subject' => 'CRGMPC Central Email Verification',
                    'template' => 'mails.verification_request',
                    'data' => (object) [
                        'option' => $this->helper
                                ->toApprovalButton(['verify'])
                                ->encrypt([
                                    Str::camel('user_request_id') => $userRequest->id,
                                    Str::camel('reference_id') => $userRequest->reference_id
                                ])
                            ->result(),
                        'id' => $userRequest->id,
                        'author' => Str::title($userRequest->author_first_name . ' ' . $userRequest->author_last_name),
                        'user_request' => Str::title($userRequest->first_name . ' ' . $userRequest->last_name),
                        'reference_id' => $userRequest->reference_id,
                        'created_date' => date('m/d/Y', strtotime($userRequest->created_datetime)),
                        'accesses' => $userRequest->userSystemAccessRequests
                    ],
                ]);
        }
    }
}
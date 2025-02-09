<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\UserRequestApproval;
use App\Services\Service;

class ApprovalsHelper
{
    protected $request;
    protected $action;
    protected $status;
    protected $userRequestApproval;
    protected $approvals;

    public function __construct(UserRequestApproval $userRequestApproval, Service $service)
    {
        $this->service = $service;
        $this->userRequestApproval = $userRequestApproval;
        $this->status = null;
    }

    public function process(Request $request)
    {

        $this->request = $request;

        return $this;

    }

    public function action($action)
    {

        $this->action = $action;

        return $this;

    }

    public function approval($model, $filterId, $foreignKey, $alias = null)
    {
        $data = $this->service
            ->model($model)
            ->query((object) [
                'show' => 'filter',
                'filter' => [
                    $alias . '.id' => $this->request->input(Str::camel($filterId)),
                ],
            ])
            ->relation(\App\Models\UserRequestApproval::class, 'user_request_approvals', 'user_request_id', $foreignKey)
            ->result()
            ->first();

        $currentApproval = $this->userRequestApproval->getWhere([
                'ura.id' => $this->request->input('id'),
                'ura.hierarchy' => $this->request->input('hierarchy'),
                'ura.user_request_id' => $this->request->input(Str::camel('user_request_id'))
            ])
            ->result()
            ->first();

        $approvalDetails = $this->approvalDetails($data->user_request_approvals);

     
        if ($data->status_code == "VRFD" || $data->status_code == "SBMTD") {
            if ($currentApproval->status_code == "PNDG") {

                if (Str::contains($this->action, ['approve', 'reject'])) {

                    if (!is_null($approvalDetails->current) and !is_null($approvalDetails->first)) {

                        if (($this->request->input('id') == $approvalDetails->current->id and
                            $this->request->input('hierarchy') == $approvalDetails->current->hierarchy and
                            $this->request->input(Str::camel('user_request_id')) == $approvalDetails->current->user_request_id) and
                            ($this->request->input('id') == $approvalDetails->first->id and
                            $this->request->input('hierarchy') == $approvalDetails->first->hierarchy and
                            $this->request->input(Str::camel('user_request_id')) == $approvalDetails->first->user_request_id) and
                            $approvalDetails->current->status_code == "PNDG" and $approvalDetails->first->status_code == "PNDG") {
                                
                            $this->request->merge([
                                Str::camel('status_datetime') => now()->toDateTimeString(),
                            ]);

                            $this->status = $this->action == 'approve' ? 'Approved' : 'Rejected';
                        }
                    }

                    if (!is_null($approvalDetails->previous) and !is_null($approvalDetails->current) and !is_null($approvalDetails->next)) {
                        if (($this->request->input('id') == $approvalDetails->current->id and
                            $this->request->input('hierarchy') == $approvalDetails->current->hierarchy and
                            $this->request->input(Str::camel('user_request_id')) == $approvalDetails->current->user_request_id) and
                            $approvalDetails->previous->status_code == "APRVD" and $approvalDetails->current->status_code == "PNDG" and $approvalDetails->next->status_code == "PNDG") {
                            $this->request->merge([
                                Str::camel('status_datetime') => now()->toDateTimeString(),
                            ]);

                            $this->status = $this->action == 'approve' ? 'Approved' : 'Rejected';
                        }
                    }

                    if (!is_null($approvalDetails->current) and !is_null($approvalDetails->last)) {
                        if (($this->request->input('id') == $approvalDetails->current->id and
                            $this->request->input('hierarchy') == $approvalDetails->current->hierarchy and
                            $this->request->input(Str::camel('user_request_id')) == $approvalDetails->current->user_request_id) and
                            ($this->request->input('id') == $approvalDetails->last->id and
                            $this->request->input('hierarchy') == $approvalDetails->last->hierarchy and
                            $this->request->input(Str::camel('user_request_id')) == $approvalDetails->last->user_request_id) and
                            $approvalDetails->current->status_code == "PNDG" and $approvalDetails->last->status_code == "PNDG") {
                            $this->request->merge([
                                Str::camel('status_datetime') => now()->toDateTimeString(),
                            ]);

                            $this->status = $this->action == 'approve' ? 'Completed' : 'Rejected';
                        }
                    }

                    if (is_null($this->status)) {
                        $this->status = 'Wrong Approver';
                    }
                }
            } else if ($currentApproval->status_code == "APRVD") {
                $this->status = 'Previously Approved';
            } else if ($currentApproval->status_code == "RJCTD") {
                $this->status = 'Previously Rejected';
            }
        } else if ($data->status_code == "CNCLD") {
            $this->status = 'Already Cancelled';
        } else if ($data->status_code == "RJCTD") {
            $this->status = 'Already Rejected';
        } else if ($data->status_code == "CMPLTD") {
            $this->status = 'Already Completed';
        }

        return $this;
    }

    public function status()
    {

        return $this->status;

    }

    public function request()
    {

        return $this->request;

    }

    public function result()
    {

        return (object) [
            'status' => $this->status,
            'request' => $this->request,
        ];

    }

    public function approvalDetails($approvals)
    {
        $sortedApprovals = $approvals->sortBy('hierarchy');
        $pendingApprovals = $sortedApprovals->where('status_code', 'PNDG');
        $currentApproval = $pendingApprovals->first();
        $previousApproval = null;
        $nextApproval = null;

        if ($currentApproval->hierarchy > 1) {
            $previousApproval = $sortedApprovals->where('hierarchy', $currentApproval->hierarchy - 1)
                ->first();
        }

        if ($currentApproval->hierarchy < $sortedApprovals->max('hierarchy')) {
            $nextApproval = $sortedApprovals->where('hierarchy', $currentApproval->hierarchy + 1)
                ->first();
        }
        
        return (object) [
            'first' => $sortedApprovals->first(),
            'previous' => $previousApproval,
            'current' => $currentApproval,
            'next' =>  $nextApproval,
            'last' => $sortedApprovals->last(),
        ];
    }
}
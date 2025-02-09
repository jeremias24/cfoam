<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helper
{
    protected $toApprovalButton = false;
    protected $approvalButtons = false;
    protected $encrypted;

    public function encrypt($data)
    {

        if ($this->toApprovalButton) {
            $approvalData = [];

            foreach ($this->approvalButtons as $approvalButton) {
                $approvalData = array_merge($approvalData, [
                    $approvalButton => base64_encode(urlencode(json_encode(array_merge($data, ['action' => Str::Title($approvalButton)])))),
                ]);
            }

            $data = (object) $approvalData;
        } else {
            $data = base64_encode(urlencode(json_encode($data)));
        }

        $this->encrypted = $data;

        return $this;

    }

    public function toApprovalButton($buttons)
    {

        $this->approvalButtons = $buttons;
        $this->toApprovalButton = true;

        return $this;

    }

    public function result()
    {

        return $this->encrypted;

    }

}
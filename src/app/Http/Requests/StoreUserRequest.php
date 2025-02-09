<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'created_by' => $this->user()->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:1000',
            'email' => 'required|string|max:1000',
            'username' => 'required|string|max:1000',
            'password' => 'required|string|max:1000',
            'created_by' => 'exists:users,id',
            'role_id' => 'required',
        ];
    }

}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'phone' => 'nullable',
            'birthday' => 'nullable|date',
            'beliefs' => 'nullable',
            'marital_status' => 'nullable',
            'address' => 'nullable',
            'work_status' => 'nullable',
            'gender' => 'nullable',
        ];
    }
}

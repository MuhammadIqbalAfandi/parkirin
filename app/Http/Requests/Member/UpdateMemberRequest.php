<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'phone' => 'required|numeric|min:12|unique:members,phone,' . $this->member->id,
            'plat_number' => 'required|string|unique:members,plat_number,' . $this->member->id,
            'type_member_id' => 'required|numeric',
        ];
    }
}
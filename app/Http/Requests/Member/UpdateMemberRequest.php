<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'phone' => 'nullable|numeric|min:12|unique:members,phone,' . $this->member->id,
            'type_member_id' => 'required|numeric',
            'vehicles.*.platNumber' => [Rule::unique('vehicles', 'plat_number')->ignore($this->member->id, 'member_id')],
        ];
    }
}

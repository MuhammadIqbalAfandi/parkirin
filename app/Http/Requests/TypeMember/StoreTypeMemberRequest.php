<?php

namespace App\Http\Requests\TypeMember;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypeMemberRequest extends FormRequest
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
            'type' => 'required|string',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ];
    }
}

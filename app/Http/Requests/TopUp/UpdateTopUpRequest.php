<?php

namespace App\Http\Requests\TopUp;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTopUpRequest extends FormRequest
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
            'balance' => 'required|numeric',
            'exp_date' => 'required|date',
        ];
    }
}

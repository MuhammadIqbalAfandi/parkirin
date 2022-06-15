<?php

namespace App\Http\Requests\TestTransaction;

use App\Models\EntryTransaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTestTransactionRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->id === 2) {
            return [
                'plat_number' => 'required',
                'entry_transaction_id' => [
                    'required',
                    Rule::in(EntryTransaction::pluck('transaction_number')),
                ],
                'type_vehicle_id' => 'required',
            ];
        } else {
            return [
                //
            ];
        }
    }
}

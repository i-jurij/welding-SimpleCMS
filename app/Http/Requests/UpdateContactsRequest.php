<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateContactsRequest extends StoreContactsRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'data' => [
                'required',
                Rule::unique('contacts')->ignore($this->id),
            ],
        ]);
    }
}

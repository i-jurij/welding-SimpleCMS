<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
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
        return [
            'alias' => ['required', 'unique:pages,alias', 'regex:/^[a-zA-Zа-яА-ЯёЁ0-9-_]{1,100}$/', 'max:100'],
            'title' => 'required|max:100',
            'description' => 'required|max:255',
            'keywords' => ['max:500'],
            'robots' => 'max:100',
            'single_page' => ['regex:/^(yes|no)$/i', 'max:10'],
            'publish' => ['regex:/^(yes|no)$/i', 'max:10'],
            'service_page' => ['regex:/^(yes|no)$/i', 'max:10'],
        ];
    }

    public function messages()
    {
        return [
            'alias.required' => 'Warning! You have to fill in the :attribute field.',
            'alias.regex' => 'Warning! The :attribute field must be only letters, numbers, dash, underscore.',
            'alias.max' => 'Warning! The :attribute field must be <100 characters.',
            'title.required' => 'Warning! You have to fill in the :attribute field.',
            'title.max' => 'Warning! The :attribute field must be <100 characters.',
            'description.required' => 'Warning! You have to fill in the :attribute field.',
            'description.max' => 'Warning! The :attribute field must be <255 characters.',
            'keywords.regex' => 'Warning!  The :attribute field must be only letters, numbers, underscore.',
            'keywords.max' => 'Warning! The :attribute field must be <500 characters.',
            'robots.max' => 'Warning! The :attribute field must be <100 characters.',
            'single_page.regex' => 'Warning!  The :attribute field must be only "yes" or "no".',
            'publish.regex' => 'Warning!  The :attribute field must be only "yes" or "no".',
            'service_page.regex' => 'Warning!  The :attribute field must be only "yes" or "no".',
        ];
    }

    public function attributes()
    {
        return [
            'alias' => 'pages alias',
            'title' => 'pages title',
            'description' => 'pages description',
        ];
    }
}

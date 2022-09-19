<?php

namespace App\Http\Requests;

use App\Traits\HttpResponses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePlayerRequest extends FormRequest
{
    use HttpResponses;
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
        return [
            'username' => 'required|unique:players,username',
            'email' => 'required|email|unique:players,email',
            'password' => 'required|min:8|confirmed'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->errors(), 'Validation Error'));
    }
}

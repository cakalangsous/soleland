<?php

namespace App\Http\Requests;

use App\Enums\CharacterGender;
use App\Traits\HttpResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreCharacterRequest extends FormRequest
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
            'parent_id' => 'required|numeric',
            'username' => 'required|unique:characters,username',
            'password' => 'required|min:8|confirmed',
            'name' => 'required',
            'gender' => [new Enum(CharacterGender::class)],
        ];
    }

    public function messages()
    {
        return [
            'gender.Illuminate\Validation\Rules\Enum' => "Please input either 'male' or 'female'"
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->errors(), 'Validation Error'));
    }
}

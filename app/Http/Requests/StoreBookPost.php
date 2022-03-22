<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookPost extends FormRequest
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
        $max="max:255";

        return [
            'title' => ['required', 'string', $max],
            'author' => ['required', 'string', $max],
            'publication_date' => ['required', 'string', $max],
        ];
    }

    public function messages()
    {
        return [
            'title.required'=>'El título es requerido.',
            'author.required'=>'El author es requerido.',
            'apellido_materno.required'=>'El apellido materno es requerido.',
            'publication_date.min'=>'La fecha de publicación es requerida.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'statusCode' => 422,
            'message'    => 'Unprocessable Entity',
            'errors'     => $validator->errors()
        ], 422);
        throw new HttpResponseException($response);
    }
}

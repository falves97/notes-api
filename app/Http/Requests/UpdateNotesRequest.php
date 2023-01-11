<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateNotesRequest extends FormRequest
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
    public function rules() {
        return [
            'title'       => 'required|unique:notes|max:255',
            'description' => 'required'
        ];
    }

    protected function failedValidation( Validator $validator ) {
        throw new HttpResponseException(
            response()->json( [
                'success' => false,
                'message' => 'Validation errors',
                'data'    => $validator->errors()
            ] )->setStatusCode( 422 )
        );
    }

    public function messages() {
        return [
            "title.required"       => "Title is required",
            "title.unique"         => "Title most to be unique",
            "title.max"            => "Title must be a maximum of 255 characters",
            "description.required" => "Description is required"
        ];
    }
}

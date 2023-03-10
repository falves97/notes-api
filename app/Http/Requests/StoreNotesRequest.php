<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreNotesRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'title'       => [
                'required',
                'string:255',
                Rule::unique('notes')->where(fn ($query) => $query->where('user_id', Auth::id()))
            ],
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

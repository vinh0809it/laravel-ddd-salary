<?php
namespace Src\User\Presentation\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $message = __('The given data was invalid.');

        throw new HttpResponseException(response()->json([
            'message' => $message,
            'errors' => $errors,
        ], 422));
    }

    public function rules(): array
    {
        return [
            "name" => ["bail", "required", "max:100"],
        ];
    }

    public function messages(): array
    {
        return [
            'name.*' => __('Tên không hợp lệ'),
        ];
    }
}

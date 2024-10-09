<?php
namespace Src\User\Presentation\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Src\User\Domain\Rules\EmailUniqueRule;

class StoreUserRequest extends FormRequest
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

    public function authorize(): bool
    {
        return true;
    }

    public function rules(EmailUniqueRule $emailUniqueRule): array
    {
        return [
            "email" => [
                "bail",
                "required",
                "string",
                "email",
                "max:100",
                $emailUniqueRule,
            ],
            "name" => ["bail", "required", "max:100"],
        ];
    }

    public function messages(): array
    {
        return [
            'email.*' => __('Email is not valid'),
            'name.*' => __('Name is not valid'),
        ];
    }
}

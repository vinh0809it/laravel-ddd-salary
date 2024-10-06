<?php
namespace Src\User\Presentation\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Src\User\Domain\Repositories\UserRepositoryInterface;
use Src\User\Domain\Rules\EmailUnique;

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

    public function rules(): array
    {
        return [
            "email" => [
                "bail",
                "required",
                "string",
                "email",
                "max:100",
                new EmailUnique(app(UserRepositoryInterface::class)),
            ],
            "name" => ["bail", "required", "max:100"],
        ];
    }

    public function messages(): array
    {
        return [
            'email.*' => __('Email không hợp lệ'),
            'name.*' => __('Tên không hợp lệ'),
        ];
    }
}

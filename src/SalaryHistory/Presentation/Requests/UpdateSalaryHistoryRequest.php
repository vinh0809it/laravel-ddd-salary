<?php
namespace Src\SalaryHistory\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Src\SalaryHistory\Domain\Rules\UserExistsRule;

class UpdateSalaryHistoryRequest extends FormRequest
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
            "on_date" => ["bail", "nullable", "date", "date_format:Y-m-d"],
            "salary" => ["bail", "nullable", "numeric", "min:0", "max:100000000"],
            "note" => ["bail", "nullable", "string", "max:200"],
        ];
    }

    public function messages(): array
    {
        return [
            'on_date.*' => __('Date is not valid'),
            'salary.*' => __('Salary is not valid'),
            'note.*' => __('Note is not valid'),
        ];
    }
}

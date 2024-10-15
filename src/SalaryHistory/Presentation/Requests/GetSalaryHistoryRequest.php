<?php
namespace Src\SalaryHistory\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class GetSalaryHistoryRequest extends FormRequest
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

    public function rules()
    {
        return [
            'from_date' => 'nullable|date|required_with:to_date',
            'to_date' => 'nullable|date|after_or_equal:from_date|required_with:from_date',
        ];
    }

    public function messages(): array
    {
        return [
            'from_date.required_with' => __('Both from date and to date must be provided together.'),
            'to_date.required_with' => __('Both from date and to date must be provided together.'),
            'to_date.after_or_equal' => __('From date must be before or equal to to date.'),
            'from_date.*' => __('Date is not valid.'),
            'to_date.*' => __('Date is not valid.'),
        ];
    }
}

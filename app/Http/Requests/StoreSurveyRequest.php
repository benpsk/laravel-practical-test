<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

class StoreSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array{name: string, phone_no: array{0: string}, gender: array{0: string, 1: In}, dob: array{0: string, 1: string, 2: string}}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:20',
            'phone_no' => ['required'],
            'gender' => ['nullable', Rule::in(['Male', 'Female']),],
            'dob' => ['required', 'date', 'before_or_equal:' . Carbon::now()->format('Y-m-d'),],
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'full_name' => trim((string) $this->input('full_name')),
            'job_title' => trim((string) $this->input('job_title')),
            'office' => trim((string) $this->input('office')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name'     => ['required', 'string', 'max:100'],
            'middle_initial' => ['nullable', 'string', 'size:1', 'alpha'],
            'last_name'      => ['required', 'string', 'max:100'],
            'suffix'         => ['nullable', 'string', 'max:20'],
            'full_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'office' => ['required', 'string', 'max:255'],
            'id_number' => ['prohibited'],
            'id_display' => ['prohibited'],
            'card_year' => ['prohibited'],
            'card_seq' => ['prohibited'],
            'photo' => ['required', 'image', 'max:5120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $name = (string) $this->input('full_name');
            $jobTitle = (string) $this->input('job_title');

            $duplicate = Employee::query()
                ->where('full_name', $name)
                ->get()
                ->contains(fn (Employee $e) => strcasecmp(trim((string) $e->job_title), $jobTitle) === 0);

            if ($duplicate) {
                $validator->errors()->add(
                    'job_title',
                    'You already have an ID card with this position. Enter a different position to register a new card.'
                );
            }
        });
    }
}
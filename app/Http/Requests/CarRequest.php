<?php

namespace App\Http\Requests;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // In a real application, you might want to check if the user has permission to manage cars
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color' => 'required|string|max:50',
            'mileage' => 'required|numeric|min:0',
            'status' => ['required', Rule::in([
                Car::STATUS_AVAILABLE,
                Car::STATUS_BOOKED,
                Car::STATUS_MAINTENANCE
            ])],
            'daily_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'specifications' => 'nullable|json',
        ];

        // For license plate, add a unique constraint that ignores the current car when updating
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['license_plate'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('cars', 'license_plate')->ignore($this->car)
            ];
        } else {
            $rules['license_plate'] = 'required|string|max:20|unique:cars,license_plate';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'brand' => 'brand name',
            'model' => 'model name',
            'year' => 'manufacturing year',
            'license_plate' => 'license plate number',
            'mileage' => 'vehicle mileage',
            'daily_rate' => 'daily rental rate',
            'specifications' => 'vehicle specifications',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'license_plate.unique' => 'A car with this license plate is already registered in the system.',
            'year.min' => 'The manufacturing year must be 1900 or later.',
            'year.max' => 'The manufacturing year cannot be in the future.',
            'daily_rate.min' => 'The daily rental rate must be a positive number.',
            'mileage.min' => 'The mileage must be a positive number.',
            'specifications.json' => 'The specifications must be in a valid JSON format.',
        ];
    }
}

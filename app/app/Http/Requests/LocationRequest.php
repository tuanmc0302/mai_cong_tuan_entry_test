<?php

namespace App\Http\Requests;

use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $location = $this->route('location'); // null when creating new

        return [
            'name'   => ['required', 'string', 'max:255',
                         Rule::unique('locations', 'name')->ignore($location?->id)],
            'lat'    => ['required', 'numeric', 'between:-90,90'],
            'lng'    => ['required', 'numeric', 'between:-180,180'],
            'status' => ['nullable', Rule::in(Location::STATUSES)],
        ];
    }
}
<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;

class RescheduleBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_date' => ['required','date','after_or_equal:today'],
            'new_start_time' => ['required','date_format:H:i'],
            'new_end_time' => ['required','date_format:H:i','after:new_start_time'],
        ];
    }

}
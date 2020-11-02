<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'user_id' => 'integer|min:1',
          'user_add_data_id' => 'integer|min:1',
          'emailAddress' => 'max:255',
          'firstName' => 'max:255',
          'lastName' => 'max:255',
          'company' => 'max:255',
          'address' => 'required|max:255',
          'city' => 'required|max:255',
          'state' => 'required|max:255',
          'zipcode' => 'max:5',
          'lat' => '',
          'lng' => '',
          'service' => 'max:300',
          'notes' => 'max:300',
          'nextScheduleDate' => 'date|date_format:Y-m-d',
          'vehicle' => 'integer',

        ];
    }
}

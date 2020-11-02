<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Sharedaccounts extends FormRequest
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
     /*   if(empty($this->input('type_of_friend')))
        {*/
        return [

            'type_of_friend' => 'sometimes|required',

        ];
        }
 /*   }  */
}

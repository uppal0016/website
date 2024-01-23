<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorStoreRequest extends FormRequest
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
    $result = [];
    if ($this->method() == 'PUT')
    {
      $result['name'] = 'required|string|unique:vendors,name,'.$this->get('id');
    }else {
      $result['name'] = 'required|string|unique:vendors,name';
    }
    return $result;
  }

  public function messages()
  {
    return [
      'name.required' => 'Vendor Name is required!',
    ];
  }
}

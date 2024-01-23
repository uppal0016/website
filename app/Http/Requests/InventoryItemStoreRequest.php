<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryItemStoreRequest extends FormRequest
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
      $result['generate_id'] = 'required|string|unique:inventory_items,generate_id,'.$this->get('id');
      $result['category_id'] = 'required';
      $result['company_name'] = 'required|string';
      $result['serial_no'] = 'required';
    }else {
      $result['generate_id'] = 'required';
      $result['category_id'] = 'required';
      $result['company_name'] = 'required|string';
      $result['serial_no'] = 'required';
    }
    return $result;
  }

  public function messages()
  {
    return [
      'generate_id.required' => 'Generate id is required!',
      'category_id.required' => 'Category is required!',
      'company_name.required' => 'Company Name is required!',
      'serial_no.required' => 'Serial No is required!',
    ];
  }
}

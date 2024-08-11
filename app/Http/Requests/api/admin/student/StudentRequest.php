<?php

namespace App\Http\Requests\api\admin\student;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // this request from admin for create new Student
            'name'=>['required'], 
            'phone'=>['required','unique:users'],
            'email'=>['required','unique:users'],
            'category_id'=>['required'],
            'language'=>['required'],
            'password'=>['required'],
            'country_id'=>['required'],
            'city_id'=>['required'],
            'parent_name'=>['required'],
            'parent_email'=>['required'],
            'parent_password'=>['required'],
            'parent_phone'=>['required'],
        ];
    }

      public function failedValidation(Validator $validator){
      throw new HttpResponseException(response()->json([
      'message'=>'validation error',
      'errors'=>$validator->errors(),
      ],400));
      }
}

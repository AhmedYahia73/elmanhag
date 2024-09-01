<?php

namespace App\Http\Requests\payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class PaymentReqeust extends FormRequest
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
            // This Is Request About All Payment
            // 'merchantCode'=>['required'],
            // 'merchantRefNum'=>['required'],
            // 'customerProfileId'=>['required'],
            // 'paymentMethod'=>['required'],
            // 'cardNumber'=>['required'],
            // 'cardExpiryYear'=>['required'],
            // 'cardExpiryMonth'=>['required'],
            // 'cvv'=>['required'],
            // 'customerName'=>['required'],
            // 'customerMobile'=>['required'],
            // 'customerEmail'=>['required'],
            // 'amount'=>['required'],
            // 'description'=>['required'],
            // 'language'=>['required'],
            // 'chargeItems'=>['required'],
            // 'currencyCode'=>['required'],
            // 'signature'=>['required'],
        ];
    }

     public function failedValidation(Validator $validator){
     throw new HttpResponseException(response()->json([
     'message'=>'validation error',
     'errors'=>$validator->errors(),
     ],400));
     }
}
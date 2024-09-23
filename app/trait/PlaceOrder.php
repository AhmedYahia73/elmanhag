<?php

namespace App\trait;

use App\Models\bundle;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait PlaceOrder
{

     protected $orderPlaceReqeust =['chargeItems','payment_method_id','merchantRefNum'];
 // This Is Trait About Make any Order 
   

    public function placeOrder(Request $request ):mixed{
        $user = $request->user();
        $newOrder = $request->only($this->orderPlaceReqeust);
        $items = $newOrder['chargeItems'];
        // $user_id = $request->user()->id;
        $new_item = [];
        foreach ($items as $item) {
            $itemId = $item['itemId'];
            $service = $item['description'];
            $item_type = $service == 'Bundle' ? 'bundle' : 'subject'; // iF Changed By Sevice Name Get Price One Of Them
           try {
             $amount = $this->$item_type->where('id',$itemId)->sum('price'); // Get Price For Item
           
            $item['student_id'] =$user->id;
            $item['purchase_date'] =now(); // Purchase Date Now
            $item['merchantRefNum'] =$newOrder['merchantRefNum']; // This Is Reference Number For Order ID
            $item['service'] =$service ; // This Is Reference Number For Order ID
            try {
                $paymentMethod = $this->paymenty_method->where('title','fawry')->first();
            } catch (QueryException $qe) {
                return response()->json([
                    'faield'=>'payment Method Don\'t Available',
                    'message'=>$qe->getMessage()
                ]);
            }
            $item['payment_method_id'] =$paymentMethod->id; // This Payment Static casue Don't Have Name Request In Item Charge
            $item['price'] = $amount ; // Price Take Amount Cause I Have just One Item
            $item['amount']=$amount;
            $createPayment = $this->payment->create($item);
            if($service == 'Bundle'){
                $newbundle = $createPayment->bundle()->sync($itemId);
              }elseif($service == 'Subject'){
                $newSubjects = $createPayment->subject()->sync($itemId);
              }
              } catch (\Throwable $th) {
              return response()->json(
                [
                    'faield'=>'Your Order Not Found',
                    'message'=>$th->getMessage()
            ],403);
              }
            $data = [
                    'chargeItems'=>[
                        'itemId'=>$itemId,
                        'description'=>$item_type,
                        'price'=>$amount,
                        'quantity'=>'1',
                    ]
            ];
              
            }
                  return $data;
    }

    public function confirmOrder( $request): mixed{
        $merchantRefNumber = $request['merchantRefNumber'];
        $customerMerchantId = $request['customerMerchantId'];
      $orderStatus = $request['orderStatus'];
            if($orderStatus == 'PAID'){
            $payment =
                $this->payment->where('merchantRefNum', $merchantRefNumber)->with('bundle', function ($query):void {
                    $query->with('users');
                }, 'subject', function ($query):void {
                    $query->with('users');
           })->first();
            $order = $payment->service == 'Bundle' ? 'bundle' : 'subject';
            if($order == 'bundle'){
                $orderBundle = $payment->bundle;
                foreach($orderBundle as $student_bundle){
                     $student_bundle->users()->sync([$student_bundle->id=>['user_id'=>$customerMerchantId]] );
                }
            }elseif($order == 'subject'){
                $orderSubject= $payment->subject;
                 foreach($orderSubject as $student_subject){
                  $student_subject->users()->sync([$student_subject->id=>['user_id'=>$customerMerchantId]] );
                 }
            }

        }
        return response()->json($request);
    }
}
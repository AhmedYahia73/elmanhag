<?php

namespace App\Http\Controllers\api\v1\admin\promocode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\admin\promocode\PromocodeRequest;

use App\Models\PromoCode;

class CreatePromocodeController extends Controller
{
    public function __construct(private PromoCode $promo_code){}
    protected $promoCodeRequest = [
        'title',
        'code',
        'value',
        'precentage',
        'usage_type',
        'usage',
        'status',
        'live',
    ];

    public function create(PromocodeRequest $request){
        // https://bdev.elmanhag.shop/admin/promoCode/add
        // Keys
        // title, code, status, value, precentage, usage_type, usage, live
        // subjects[], bundles[]
        $promocode_data = $request->only($this->promoCodeRequest);
        $promo_code = $this->promo_code->create($promocode_data); // create promo code
        // add subjects that have this promocode
        foreach ($request->subjects as $item) {
            $promo_code->subjects()->attach($item); // add subjects to pivot table
        }
        // add bundles that have this promocode
        foreach ($request->bundles as $item) {
            $promo_code->bundles()->attach($item); // add bundles to pivot table
        }

        return response()->json([
            'success' => 'you add data success'
        ]);
    }
    
    public function modify(PromocodeRequest $request, $id){
        // https://bdev.elmanhag.shop/admin/promoCode/update/{id}
        // Keys
        // title, code, status, value, precentage, usage_type, usage, live
        // subjects[], bundles[]
        $promocode_data = $request->only($this->promoCodeRequest);
        $promo_code = $this->promo_code->where('id', $id)
        ->first();
        $promo_code->update($promocode_data); // update promo code
        
        $promo_code->subjects()->sync($request->subjects); // update subjects to pivot table
        $promo_code->bundles()->sync($request->bundles); // update bundles to pivot table

        return response()->json([
            'success' => 'you update data success'
        ]);
    }
    
    public function delete($id){
        // https://bdev.elmanhag.shop/admin/promoCode/delete/{id}
        $this->promo_code
        ->where('id', $id)
        ->delete();

        return response()->json([
            'success' => 'You delete data success'
        ]);
    }
}

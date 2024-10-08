<?php

namespace App\Http\Controllers\api\v1\admin\promocode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PromoCode;
use App\Models\category;
use App\Models\subject;
use App\Models\bundle;

class PromocodeController extends Controller
{
    public function __construct(private PromoCode $promo_code, 
    private category $categories, private subject $subjects, private bundle $bundles){}

    public function show(){
        // https://bdev.elmanhag.shop/admin/promoCode
        $promo_codes = $this->promo_code
        ->with(['subjects', 'bundles'])
        ->get();
        $categories = $this->categories
        ->where('category_id', '!=', null)
        ->get();
        $subjects = $this->subjects->get();
        $bundles = $this->bundles->get();

        return response()->json([
            'promo_codes' => $promo_codes,
            'categories' => $categories,
            'subjects' => $subjects,
            'bundles' => $bundles,
        ]);
    }
}

<?php

namespace App\Services;
use Illuminate\Http\Request;

class DiscountService
{
    public function discountBill(Request $request)
    {
        $discount = 0;
        if ($request->quantity >= 7) {
            $discount += 7;
        }
        if ($request->hasWshirt != 0 && $request->hasTie != 0) {
            $discount += 5;
        }
        
        return $discount;
    }
}

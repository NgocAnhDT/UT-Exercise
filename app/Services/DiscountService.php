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
        if (filter_var($request->hasWshirt, FILTER_VALIDATE_BOOLEAN) == true 
            && filter_var($request->hasTie, FILTER_VALIDATE_BOOLEAN) == true) {
            $discount += 5;
        }
        
        return $discount;
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\DiscountService;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function totalBill(Request $request)
    {
        if(!ctype_digit($request->quantity)) {
            return view('bill')->with('error', 'Something went wrong!');
        }
        $discount = $this->discountService->discountBill($request);

        return view('bill', compact('discount'));
    }
}

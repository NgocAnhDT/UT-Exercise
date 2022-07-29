<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderBeerService
{
    public function order(Request $request)
    {
        $totalPrice = 0;
        $checkFirstTime1 = false;
        $checkFirstTime2 = false;
        foreach($request->orders as $order) {
            $time = Carbon::parse($order['time']);
            if ($time->lt(Carbon::parse('16:00'))) {
                $totalPrice += $order['numCup'] * 490;
                $checkFirstTime1 = true;
            } elseif ($time->gte(Carbon::parse('16:00')) && $time->lt(Carbon::parse('18:00'))) {
                $totalPrice += $order['numCup'] * 290;
                $checkFirstTime2 = true;
            } else {
                $totalPrice += $order['numCup'] * 490;
            }
        }
        if (filter_var($request->hasVoucher, FILTER_VALIDATE_BOOL) == true) {
            $totalPrice -= $checkFirstTime1 ? 390 : ($checkFirstTime2 ? 190 : 390);
        }

        return $totalPrice;
    }
}

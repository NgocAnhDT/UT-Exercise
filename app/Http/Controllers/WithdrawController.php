<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function withdraw(Request $request)
    {
        $now = Carbon::now();
        if ($request->isVip == 1) {
            $fee = 0;
        } else {
            if (in_array($now->format('l'), ['Saturday','Sunday'])) {
                $fee = 110;
            } else {
                if ($now->gte(Carbon::parse('8:45')) && $now->lt(Carbon::parse('18:00'))) {
                    $fee = 0;
                } else {
                    $fee = 110;
                }
            }
        }

        return view('index', compact('now', 'fee'));
    }
}

<?php

namespace App\Services;

use Carbon\Carbon;
use Cmixin\BusinessTime;
use Illuminate\Http\Request;

class WithdrawService
{
    public function withdraw(Request $request)
    {
        BusinessTime::enable(Carbon::class);
        Carbon::setHolidaysRegion('vn');
        $now = Carbon::now();
        if ($request->isVip == 1) {
            return 0;
        }
        if (in_array($now->format('l'), ['Saturday','Sunday']) || $now->isHoliday()) {
            return 110;
        }
        if ($now->gte(Carbon::parse('8:45')) && $now->lt(Carbon::parse('18:00'))) {
            return 0;
        }
        return 110;
    }
}

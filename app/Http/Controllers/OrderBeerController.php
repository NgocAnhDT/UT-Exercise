<?php

namespace App\Http\Controllers;

use App\Services\OrderBeerService;
use Illuminate\Http\Request;

class OrderBeerController extends Controller
{
    protected $orderService;

    public function __construct(OrderBeerService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function order(Request $request)
    {
        foreach($request->orders as $order) {
            if(!ctype_digit($order['numCup'])) {
                return view('order')->with('error', 'Something went wrong!');
            }
        }

        $total = $this->orderService->order($request);

        return view('order', compact('total'));
    }
}

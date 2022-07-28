<?php

namespace App\Http\Controllers;

use App\Services\WithdrawService;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    protected $withdrawService;

    public function __construct(WithdrawService $withdrawService)
    {
        $this->withdrawService = $withdrawService;
    }

    public function withdraw(Request $request)
    {
        $fee = $this->withdrawService->withdraw($request);

        return view('index', compact('fee'));
    }
}

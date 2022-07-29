<?php

namespace Tests\Unit\Services;

use App\Services\OrderBeerService;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class OrderBeerServiceTest extends TestCase
{
    protected $service;

    public function setUp() : void
    {
        parent::setUp();
        $this->service = new OrderBeerService();
    }

    public function tearDown() : void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function test_order_from_16h_to_18h_and_has_not_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '17:00',
                    'numCup' => '1',
                ]
            ],
            'hasVoucher' => false,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(290, $result);
    }

    public function test_order_from_16h_to_18h_and_has_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '17:00',
                    'numCup' => '1',
                ]
            ],
            'hasVoucher' => true,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(100, $result);
    }
    
    public function test_order_at_16h_and_has_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '16:00',
                    'numCup' => '2',
                ]
            ],
            'hasVoucher' => true,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(390, $result);
    }

    public function test_order_at_16h_and_has_not_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                'time' => '16:00',
                'numCup' => '1',
                ],
            ],
            'hasVoucher' => false,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(290, $result);
    }

    public function test_order_before_16h_and_has_not_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '15:00',
                    'numCup' => '1',
                ],
            ],
            'hasVoucher' => false,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(490, $result);
    }

    public function test_order_before_16h_and_has_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '15:00',
                    'numCup' => '2',
                ],
            ],
            'hasVoucher' => true,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(590, $result);
    }

    public function test_order_after_18h_and_has_not_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '18:01',
                    'numCup' => '1',
                ],
            ],
            'hasVoucher' => false,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(490, $result);
    }

    public function test_order_after_18h_and_has_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '18:01',
                    'numCup' => '2',
                ],
            ],
            'hasVoucher' => true,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(590, $result);
    }

    public function test_order_exist_and_has_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '15:01',
                    'numCup' => '1',
                ],
                [
                    'time' => '16:01',
                    'numCup' => '1',
                ],
                [
                    'time' => '18:01',
                    'numCup' => '1',
                ],
            ],
            'hasVoucher' => true,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(880, $result);
    }

    public function test_order_exist_and_has_not_voucher()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '15:01',
                    'numCup' => '1',
                ],
                [
                    'time' => '16:01',
                    'numCup' => '1',
                ],
                [
                    'time' => '18:01',
                    'numCup' => '1',
                ],
            ],
            'hasVoucher' => false,
        ]);
        $result = $this->service->order($request);
        $this->assertEquals(1270, $result);
    }
}

<?php

namespace Tests\Unit\Service;

use App\Services\DiscountService;
use Tests\TestCase;
use Illuminate\Http\Request;

class DiscountServiceTest extends TestCase
{
    protected $service;

    public function setUp() : void
    {
        parent::setUp();
        $this->service = new DiscountService();
    }

    public function tearDown() : void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function test_discount_bill_function_with_quantity_less_than_7_and_has_not_wshirt_and_tie()
    {
        $request = new Request([
            'quantity' => '5',
            'hasWshirt' => 'false',
            'hasTie' => 'false',
        ]);
        $result =  $this->service->discountBill($request);
        $this->assertEquals(0, $result);
    }

    public function test_discount_bill_function_with_quantity_equal_7()
    {
        $request = new Request([
            'quantity' => '7',
            'hasWshirt' => 'false',
            'hasTie' => 'false',
        ]);
        $result =  $this->service->discountBill($request);
        $this->assertEquals(7, $result);
    }

    public function test_discount_bill_function_with_quantity_greater_than_7_and_has_not_wshirt()
    {
        $request = new Request([
            'quantity' => '8',
            'hasWshirt' => 'false',
            'hasTie' => 'true',
        ]);
        $result =  $this->service->discountBill($request);
        $this->assertEquals(7, $result);
    }

    public function test_discount_bill_function_with_quantity_greater_than_7_and_has_not_tie()
    {
        $request = new Request([
            'quantity' => '8',
            'hasWshirt' => 'true',
            'hasTie' => 'false',
        ]);
        $result =  $this->service->discountBill($request);
        $this->assertEquals(7, $result);
    }

    public function test_discount_bill_function_with_quantity_greater_than_7_and_has_wshirt_and_tie()
    {
        $request = new Request([
            'quantity' => '8',
            'hasWshirt' => 'true',
            'hasTie' => 'true',
        ]);
        $result =  $this->service->discountBill($request);
        $this->assertEquals(12, $result);
    }

    public function test_discount_bill_function_with_quantity_less_than_7_and_has_wshirt_and_tie()
    {
        $request = new Request([
            'quantity' => '5',
            'hasWshirt' => 'true',
            'hasTie' => 'true',
        ]);
        $result =  $this->service->discountBill($request);
        $this->assertEquals(5, $result);
    }
}

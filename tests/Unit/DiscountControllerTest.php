<?php

namespace Tests\Unit;

use App\Http\Controllers\DiscountController;
use App\Services\DiscountService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;

class DiscountControllerTest extends TestCase
{
    protected $controller;
    protected $serviceMock;

    public function setUp() : void
    {
        parent::setUp();
        $this->serviceMock = Mockery::mock($this->app->make(DiscountService::class));
        $this->controller = new DiscountController($this->serviceMock);
    }

    public function tearDown() : void
    {
        Mockery::close();
        unset($this->controler);
        parent::tearDown();
    }
    public function test_total_bill_function_with_quantity_is_not_positive_integer()
    {
        $request = new Request([
            'quantity' => '3.14',
        ]);
        $view = $this->controller->totalBill($request);
        $this->assertEquals('bill', $view->getName());
        $this->assertArrayHasKey('error', $view->getData());
    }

    public function test_total_bill_function_with_quantity_is_positive_integer()
    {
        $request = new Request([
            'quantity' => '7',
        ]);
        $discount = 7;
        $this->serviceMock->shouldReceive('discountBill')->andReturn($discount);
        $view = $this->controller->totalBill($request);
        $this->assertEquals('bill', $view->getName());
        $this->assertArrayHasKey('discount', $view->getData());
    }
}

<?php

namespace Tests\Unit;

use App\Http\Controllers\OrderBeerController;
use App\Services\OrderBeerService;
use Mockery;
use Tests\ControllerTestCase;
use Illuminate\Http\Request;

class OrderBeerControllerTest extends ControllerTestCase
{
    protected $controller;
    protected $serviceMock;

    public function setUp() : void
    {
        parent::setUp();
        $this->serviceMock = Mockery::mock($this->app->make(OrderBeerService::class));
        $this->controller = new OrderBeerController($this->serviceMock);
    }

    public function tearDown() : void
    {
        Mockery::close();
        unset($this->controler);
        parent::tearDown();
    }

    public function test_order_function_with_number_of_cups_is_not_positive_integer()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '17:00',
                    'numCup' => '3.14',
                ]
            ],
            'hasVoucher' => false,
        ]);
        $view = $this->controller->order($request);
        $this->assertEquals('order', $view->getName());
        $this->assertArrayHasKey('error', $view->getData());
    }

    public function test_order_function_with_number_of_cups_is_positive_integer()
    {
        $request = new Request([
            'orders' => [
                [
                    'time' => '14:00',
                    'numCup' => '1',
                ]
            ],
            'hasVoucher' => false,
        ]);
        $price = 490;
        $this->serviceMock->shouldReceive('order')->andReturn($price);
        $view = $this->controller->order($request);
        $this->assertEquals('order', $view->getName());
        $this->assertArrayHasKey('total', $view->getData());
    }
}

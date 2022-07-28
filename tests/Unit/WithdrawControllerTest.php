<?php

namespace Tests\Unit;

use App\Http\Controllers\WithdrawController;
use App\Services\WithdrawService;
use Mockery;
use Tests\ControllerTestCase;
use Illuminate\Http\Request;

class WithdrawControllerTest extends ControllerTestCase
{
    protected $controller;
    protected $serviceMock;

    public function setUp() : void
    {
        parent::setUp();
        $this->serviceMock = Mockery::mock($this->app->make(WithdrawService::class));
        $this->controller = new WithdrawController($this->serviceMock);
    }

    public function tearDown() : void
    {
        Mockery::close();
        unset($this->controler);
        parent::tearDown();
    }

    public function test_function_withdraw()
    {
        $request = new Request([
            'isVip' => 1,
        ]);
        $fee = 0;
        $this->serviceMock->shouldReceive('withdraw')->andReturn($fee);
        $view = $this->controller->withdraw($request);
        $this->assertEquals('index', $view->getName());
        $this->assertArrayHasKey('fee', $view->getData());
    }
}

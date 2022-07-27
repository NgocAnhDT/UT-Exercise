<?php

namespace Tests\Unit;

use App\Http\Controllers\WithdrawController;
use Carbon\Carbon;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class WithdrawControllerTest extends TestCase
{
    protected $controller;

    public function setUp() : void
    {
        parent::setUp();
        $this->controller = new WithdrawController();
    }

    public function tearDown() : void
    {
        Mockery::close();
        unset($this->controler);
        parent::tearDown();
    }

    public function test_with_draw_as_vip_customer()
    {
        $request = new Request([
            'isVip' => 1,
        ]);
        $fee = 0;
        $view = $this->controller->withdraw($request);
        $this->assertEquals('index', $view->getName());
        $this->assertArrayHasKey('now', $view->getData());
        $this->assertArrayHasKey('fee', $view->getData());
        $this->assertEquals($fee, $view->getData()['fee']);
    }

    public function test_with_draw_on_weekend_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-07-23 23:30:30');
        $fee = 110;
        $weekend = ['Saturday','Sunday'];
        $this->assertContains(Carbon::now()->format('l'), $weekend);
        $view = $this->controller->withdraw($request);
        $this->assertEquals('index', $view->getName());
        $this->assertArrayHasKey('now', $view->getData());
        $this->assertArrayHasKey('fee', $view->getData());
        $this->assertEquals($fee, $view->getData()['fee']);

    }

    public function test_with_draw_not_on_weekend_and_fee_not_equal_zero_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-07-26 08:44:00');
        $weekend = ['Saturday','Sunday'];
        $this->assertNotContains(Carbon::now()->format('l'), $weekend);
        $view = $this->controller->withdraw($request);
        $this->assertEquals('index', $view->getName());
        $this->assertArrayHasKey('now', $view->getData());
        $this->assertArrayHasKey('fee', $view->getData());
        $this->assertEquals(110, $view->getData()['fee']);
    }

    public function test_with_draw_not_on_weekend_and_fee_equal_zero_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-07-26 17:00:00');
        $fee = 0;
        $weekend = ['Saturday','Sunday'];
        $this->assertNotContains(Carbon::now()->format('l'), $weekend);
        $view = $this->controller->withdraw($request);
        $this->assertEquals('index', $view->getName());
        $this->assertArrayHasKey('now', $view->getData());
        $this->assertArrayHasKey('fee', $view->getData());
        $this->assertEquals($fee, $view->getData()['fee']);
    }
}

<?php

namespace Tests\Unit\Services;

use App\Services\WithdrawService;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class WithdrawServiceTest extends TestCase
{
    protected $service;

    public function setUp() : void
    {
        parent::setUp();
        $this->service = new WithdrawService();
    }

    public function tearDown() : void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function test_with_draw_as_vip_customer()
    {
        $request = new Request([
            'isVip' => 1,
        ]);
        $result = $this->service->withdraw($request);
        $this->assertEquals(0, $result);
    }

    public function test_with_draw_on_weekend_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-07-23 23:30:30');
        $weekend = ['Saturday','Sunday'];
        $this->assertContains(Carbon::now()->format('l'), $weekend);
        $result = $this->service->withdraw($request);
        $this->assertEquals(110, $result);
    }

    public function test_with_draw_on_holiday_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-09-02 23:30:30');
        $weekend = ['Saturday','Sunday'];
        $this->assertNotContains(Carbon::now()->format('l'), $weekend);
        $result = $this->service->withdraw($request);
        $this->assertEquals(110, $result);
    }

    public function test_with_draw_not_on_weekend_and_before_8h45_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-07-26 08:44:00');
        $weekend = ['Saturday','Sunday'];
        $this->assertNotContains(Carbon::now()->format('l'), $weekend);
        $result = $this->service->withdraw($request);
        $this->assertEquals(110, $result);
    }

    public function test_with_draw_not_on_weekend_and_from_8h45_to_18h_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-07-26 17:00:00');
        $weekend = ['Saturday','Sunday'];
        $this->assertNotContains(Carbon::now()->format('l'), $weekend);
        $result = $this->service->withdraw($request);
        $this->assertEquals(0, $result);
    }

    public function test_with_draw_on_weekday_at_8h45_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-07-26 08:45:00');
        $weekend = ['Saturday','Sunday'];
        $this->assertNotContains(Carbon::now()->format('l'), $weekend);
        $result = $this->service->withdraw($request);
        $this->assertEquals(0, $result);
    }

    public function test_with_draw_not_on_weekend_at_18h_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-07-26 18:00:00');
        $weekend = ['Saturday','Sunday'];
        $this->assertNotContains(Carbon::now()->format('l'), $weekend);
        $result = $this->service->withdraw($request);
        $this->assertEquals(110, $result);
    }

    public function test_with_draw_not_on_weekend_after_18h_as_normal_customer()
    {
        $request = new Request([
            'isVip' => 0,
        ]);
        Carbon::setTestNow('2022-07-26 18:10:00');
        $weekend = ['Saturday','Sunday'];
        $this->assertNotContains(Carbon::now()->format('l'), $weekend);
        $result = $this->service->withdraw($request);
        $this->assertEquals(110, $result);
    }
}

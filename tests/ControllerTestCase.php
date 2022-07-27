<?php
namespace Tests;

use Tests\TestCase;
use Illuminate\View\View;

abstract class ControllerTestCase extends TestCase
{
    public function assertViewResponse($viewResponse, $viewName, $dataFields = [])
    {
        $this->assertInstanceOf(View::class, $viewResponse);
        $this->assertEquals($viewResponse->name(), $viewName);
        foreach($dataFields as $field) {
            $this->assertArrayHasKey($field, $viewResponse->getData());
        }
    }
}

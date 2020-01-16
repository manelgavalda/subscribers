<?php

namespace Tests\Feature;

use App\Field;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetSubscribersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function subscribers_can_be_retrieved()
    {
    	$subscribers = factory('App\Subscriber', 10)->create();

    	$response = $this->get('api/subscribers')->assertOk();

    	$this->assertEquals($subscribers->count(), $response->original->count());
    	$this->assertTrue($subscribers->first()->is($response->original->first()));
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetSubscribersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function subscribers_are_retrieved_with_fields()
    {
    	$subscribers = factory('App\Subscriber', 3)->create()
	    	->each(fn ($subscriber, $id) =>
				factory('App\Field')->create(['subscriber_id' => $id])
	    	);

    	$this->get('api/subscribers')
    		->assertOk()
    		->assertJsonCount(3)
    		->assertJsonFragment([
    			'id' => $subscribers->first()->id,
    			'name' => $subscribers->first()->name,
    			'email' => $subscribers->first()->email,
    			'state' => $subscribers->first()->state
    		])
    		->assertSee($subscribers->first()->fields->first());
    }
}

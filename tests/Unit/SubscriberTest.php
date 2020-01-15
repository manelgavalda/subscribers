<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriberTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function it_can_have_many_fields()
    {
    	$subscriber = factory('App\Subscriber')->create();

    	factory('App\Field', 2)->create([
    		'subscriber_id' => $subscriber->id
    	]);

    	$this->assertCount(2, $subscriber->fields);
    }
}

<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
	use DatabaseMigrations;

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

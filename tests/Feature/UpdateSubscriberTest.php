<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateSubscriberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_subscriber_state_doesnt_change_when_updated_unless_is_created_explicitly()
    {
    	$subscriberA = factory('App\Subscriber')->create([
    		'name' => 'Old Name',
    		'email' => 'oldemail@mailerlite.com',
    		'state' => 'unsubscribed'
    	]);

    	$subscriberB = factory('App\Subscriber')->create([
    		'name' => 'Another Old Name',
    		'email' => 'anotheroldemail@mailerlite.com',
    		'state' => 'unsubscribed'
    	]);

        $this->put("/api/subscribers/{$subscriberA->id}", [
            'name' => 'New Name',
            'email' => 'newemail@mailerlite.com'
        ])->assertOk();

        $this->put("/api/subscribers/{$subscriberB->id}", [
            'name' => 'Another New Name',
            'email' => 'anothernewemail@mailerlite.com',
            'state' => 'active'
        ])->assertOk();

        $newSubscriberA = $subscriberA->fresh();
        $newSubscriberB = $subscriberB->fresh();

        $this->assertEquals('New Name', $newSubscriberA->fresh()->name);
        $this->assertEquals('newemail@mailerlite.com', $newSubscriberA->email);
        $this->assertEquals('unsubscribed', $newSubscriberA->state);

        $this->assertEquals('Another New Name', $newSubscriberB->fresh()->name);
        $this->assertEquals('anothernewemail@mailerlite.com', $newSubscriberB->email);
        $this->assertEquals('active', $newSubscriberB->state);
    }
}

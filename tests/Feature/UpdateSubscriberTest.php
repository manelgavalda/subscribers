<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateSubscriberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_subscriber_name_and_email_are_required_when_updating()
    {
        $subscriber = factory('App\Subscriber')->create();

        $this->put("/api/subscribers/{$subscriber->id}", [
            'name' => null,
            'email' => null
        ])->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.'
        ]);
    }

    /** @test */
    public function a_subscriber_email_must_be_in_a_valid_format_when_updating()
    {
        $subscriber = factory('App\Subscriber')->create();

        $this->put("/api/subscribers/{$subscriber->id}", [
            'name' => 'Manel',
            'email' => 'e'
        ])->assertSessionHasErrors([
            'email' => 'The email must be a valid email address.'
        ]);
    }

    /** @test */
    public function a_subscriber_email_domain_must_be_active_when_updating()
    {
        $subscriber = factory('App\Subscriber')->create();

        $this->put("/api/subscribers/{$subscriber->id}", [
            'name' => 'Manel',
            'email' => 'manelgavalda@inactivedomain.com'
        ])->assertSessionHasErrors([
            'email' => 'The email domain must be active.'
        ]);
    }

    /** @test */
    public function a_subscriber_email_must_be_unique_when_updating()
    {
        $subscriber = factory('App\Subscriber')->create([
            'email' => 'manel@gmail.com'
        ]);

        $subscriberB = factory('App\Subscriber')->create([
            'email' => 'manelB@gmail.com'
        ]);

        $this->put("/api/subscribers/{$subscriberB->id}", [
            'email' => 'manel@gmail.com'
        ])->assertSessionHasErrors([
            'email' => 'The email has already been taken.'
        ]);
    }

    /** @test */
    public function a_subscriber_can_be_partially_updated()
    {
        $subscriber = factory('App\Subscriber')->create([
            'name' => 'Manel',
            'email' => 'manelgavalda@gmail.com'
        ]);

        $this->put("/api/subscribers/{$subscriber->id}", [
            'name' => 'New Name',
            'email' => 'manelgavalda@gmail.com'
        ])->assertOk();
    }

    /** @test */
    public function a_subscriber_state_doesnt_change_when_updated_unless_is_created_explicitly_when_updating()
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

        $subscriberA = $subscriberA->fresh();
        $subscriberB = $subscriberB->fresh();

        $this->assertEquals('New Name', $subscriberA->name);
        $this->assertEquals('newemail@mailerlite.com', $subscriberA->email);
        $this->assertEquals('unsubscribed', $subscriberA->state);

        $this->assertEquals('Another New Name', $subscriberB->fresh()->name);
        $this->assertEquals('anothernewemail@mailerlite.com', $subscriberB->email);
        $this->assertEquals('active', $subscriberB->state);
    }
}

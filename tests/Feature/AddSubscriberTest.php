<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddSubscriberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_subscriber_requires_a_name_and_an_email()
    {
        $this->post('/api/subscribers', [
            'name' => null,
            'email' => null
        ])->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.'
        ]);
    }

    /** @test */
    public function a_subscriber_email_must_be_in_a_valid_format()
    {
        $this->post('/api/subscribers', [
            'name' => 'Manel',
            'email' => 'e'
        ])->assertSessionHasErrors([
            'email' => 'The email must be a valid email address.'
        ]);
    }

    /** @test */
    public function a_subscriber_email_domain_must_be_active()
    {
        $this->post('/api/subscribers', [
            'name' => 'Manel',
            'email' => 'manelgavalda@inactivedomain.com'
        ])->assertSessionHasErrors([
            'email' => 'The email domain must be active.'
        ]);
    }

    /** @test */
    public function a_subscriber_email_must_be_unique()
    {
        factory('App\Subscriber')->create([
            'email' => 'manel@gmail.com'
        ]);

        $this->post('/api/subscribers', [
            'name' => 'Manel',
            'email' => 'manel@gmail.com'
        ])->assertSessionHasErrors([
            'email' => 'The email has already been taken.'
        ]);
    }

    /** @test */
    public function a_subscriber_can_be_created_with_correct_data()
    {
        $attributes = [
            'name' => 'Manel',
            'email' => 'manel@gmail.com'
        ];

        $this->post('/api/subscribers', $attributes)
            ->assertCreated()
            ->assertJsonFragment($attributes);

        $this->assertDatabaseHas('subscribers', $attributes);
    }

    /** @test */
    public function a_subscriber_is_unconfirmed_by_default_unless_is_created_explicitly()
    {
        $this->post('/api/subscribers', [
            'name' => 'Unconfirmed User',
            'email' => 'unconfirmeduser@mailerlite.com'
        ])->assertCreated();

        $this->post('/api/subscribers', [
            'name' => 'Active User',
            'email' => 'activeuser@mailerlite.com',
            'state' => 'active'
        ])->assertCreated();

        $this->assertDatabaseHas('subscribers', [
            'name' => 'Unconfirmed User',
            'email' => 'unconfirmeduser@mailerlite.com',
            'state' => 'unconfirmed'
        ])->assertDatabaseHas('subscribers', [
            'name' => 'Active User',
            'email' => 'activeuser@mailerlite.com',
            'state' => 'active'
        ]);
    }
}

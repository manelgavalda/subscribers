<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddSubscriberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_subscriber_requires_a_title_and_an_email()
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
    public function the_subscriber_email_must_be_in_a_valid_format()
    {
        $this->post('/api/subscribers', [
            'name' => 'Manel',
            'email' => 'invalidemailformat'
        ])->assertSessionHasErrors([
            'email' => 'The email must be a valid email address.'
        ]);
    }

    // Internet connection for this
    /** @test */
    public function the_subscriber_email_domain_must_be_active()
    {
        $this->post('/api/subscribers', [
            'name' => 'Manel',
            'email' => 'manelgavalda@inactivedomain.com'
        ])->assertSessionHasErrors([
            'email' => 'The email domain must be active.'
        ]);
    }

    /** @test */
    public function the_subscriber_is_unconfirmed_by_default_unless_is_created_implicitly()
    {
        $this->post('/api/subscribers', [
            'name' => 'Unconfirmed User',
            'email' => 'unconfirmeduser@mailerlite.com'
        ]);

        $this->post('/api/subscribers', [
            'name' => 'Active User',
            'email' => 'activeuser@mailerlite.com',
            'state' => 'active',
        ]);

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

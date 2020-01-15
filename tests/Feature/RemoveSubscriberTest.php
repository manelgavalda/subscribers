<?php

namespace Tests\Feature;

use App\Field;
use App\Subscriber;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveSubscriberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_subscriber_can_be_deleted()
    {
    	$subscriber = factory('App\Subscriber')->create();

    	$this->assertCount(1, Subscriber::all());

    	$this->delete("api/subscribers/{$subscriber->id}")
    		->assertStatus(204);

    	$this->assertCount(0, Subscriber::all());
    }

    /** @test */
    public function a_subscriber_fields_are_deleted_with_the_subscriber()
    {
    	$subscriber = factory('App\Subscriber')->create();

        factory('App\Field', 2)->create([
            'subscriber_id' => $subscriber->id
        ]);

    	$this->assertCount(2, Field::all());

    	$this->delete("api/subscribers/{$subscriber->id}")
    		->assertStatus(204);

    	$this->assertCount(0, Field::all());

    }
}

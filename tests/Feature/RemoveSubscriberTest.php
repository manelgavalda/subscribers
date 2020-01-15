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
    public function the_subscriber_can_be_deleted()
    {
    	$subscriber = factory('App\Subscriber')->create();

    	$this->assertCount(1, Subscriber::all());

    	$this->delete("api/subscribers/{$subscriber->id}")
    		->assertOk();

    	$this->assertCount(0, Subscriber::all());
    }

    /** @test */
    public function the_subscriber_fields_are_deleted_with_the_subscriber()
    {
    	$subscriber = factory('App\Subscriber')->create();

    	$subscriber->fields()->create([
    		'title' => 'birthdate',
    		'value' => today()->subYears(22)->format('Y-m-d'),
    		'type' => 'date'
    	]);

    	$subscriber->fields()->create([
    		'title' => 'birthplace',
    		'value' => 'Polonia',
    		'type' => 'string'
    	]);

    	$this->assertCount(2, Field::all());

    	$this->delete("api/subscribers/{$subscriber->id}")
    		->assertOk();

    	$this->assertCount(0, Field::all());

    }
}

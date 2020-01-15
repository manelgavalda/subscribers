<?php

namespace Tests\Feature;

use App\Field;
use App\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemoveFieldTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_field_can_be_deleted()
    {
    	$field = factory('App\Field')->create();

    	$this->assertEquals(1, Field::count());

        $this->delete("/api/fields/{$field->id}")
        	->assertStatus(204);

    	$this->assertEquals(0, Field::count());
    }
}

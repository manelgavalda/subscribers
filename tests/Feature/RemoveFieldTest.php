<?php

namespace Tests\Feature;

use App\Field;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

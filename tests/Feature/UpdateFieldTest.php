<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateFieldTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_field_title_a_type_and_a_subscriber_are_required()
    {
    	$field = factory('App\Field')->create();

        $this->put("/api/fields/{$field->id}", [
            'title' => null,
            'type' => null,
            'subscriber_id' => null
        ])->assertSessionHasErrors([
            'title' => 'The title field is required.',
            'type' => 'The type field is required.',
            'subscriber_id' => 'The subscriber id field is required.'
        ]);
    }

    /** @test */
    public function a_field_can_be_updated_and_it_is_returned()
    {
    	$field = factory('App\Field')->create([
            'title' => 'birthdate',
            'type' => 'date',
            'value' => today()->subYears(22)->format('Y-m-d'),
            'subscriber_id' => 1
    	]);

        $newAttributes = [
            'title' => 'birthplace',
            'type' => 'string',
            'value' => 'Amsterdam',
            'subscriber_id' => 2
        ];

    	$this->put("/api/fields/{$field->id}", $newAttributes)
            ->assertJsonFragment($newAttributes);

    	$field = $field->fresh();

    	$this->assertEquals('birthplace', $field->title);
    	$this->assertEquals('string', $field->type);
    	$this->assertEquals('Amsterdam', $field->value);
    	$this->assertEquals(2, $field->subscriber_id);
    }
}

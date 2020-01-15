<?php

namespace App\Http\Controllers;

use App\Field;

class FieldController extends Controller
{
    public function store()
    {
    	return Field::create($this->validatedAttributes());
    }

    public function update(Field $field)
    {
    	$field->update($this->validatedAttributes());

        return response($field, 200);
    }

    public function destroy(Field $field)
    {
    	$field->delete();

        return response($field, 204);
    }

    protected function validatedAttributes()
    {
    	return request()->validate([
    		'title' => 'required',
    		'type' => 'required',
    		'value' => 'required',
    		'subscriber_id' => 'required'
    	]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Field;

class FieldController extends Controller
{
    public function store()
    {
        request()->validate([
            'title' => 'required',
            'type' => 'required',
            'subscriber_id' => 'required'
        ]);

    	return Field::create(request()->all());
    }

    public function update(Field $field)
    {
        request()->validate([
            'title' => 'required',
            'type' => 'required',
            'value' => 'required',
            'subscriber_id' => 'required'
        ]);

    	return tap($field)->update(request()->all());
    }

    public function destroy(Field $field)
    {
    	$field->delete();

        return response($field, 204);
    }
}

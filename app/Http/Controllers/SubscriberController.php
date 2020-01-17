<?php

namespace App\Http\Controllers;

use App\Subscriber;
use App\Rules\ActiveDomain;

class SubscriberController extends Controller
{
    public function index()
    {
        return Subscriber::all();
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
            'email' => [
                'bail',
                'required',
                'email',
                'unique:subscribers',
                new ActiveDomain
            ]
        ]);

    	$subscriber = Subscriber::create(request()->all());

    	foreach (request('fields', []) as $field) {
    		$subscriber->fields()->create($field);
    	}

        return response($subscriber, 201);
    }

    public function update(Subscriber $subscriber)
    {
        request()->validate([
            'name' => 'required',
            'email' => [
                'bail',
                'required',
                'email',
                "unique:subscribers,email,{$subscriber->id}",
                new ActiveDomain
            ]
        ]);

    	return tap($subscriber)->update(request()->all());
    }

    public function destroy(Subscriber $subscriber)
    {
    	$subscriber->delete();

        $subscriber->fields()->delete();

        return response($subscriber, 204);
    }
}

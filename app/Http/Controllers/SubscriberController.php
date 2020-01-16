<?php

namespace App\Http\Controllers;

use App\Subscriber;

class SubscriberController extends Controller
{
    public function index()
    {
        return Subscriber::all();
    }

    public function store()
    {
        $this->validateRequest();

    	$subscriber = Subscriber::create(request()->all());

    	foreach (request('fields', []) as $field) {
    		$subscriber->fields()->create($field);
    	}

        return response($subscriber, 201);
    }

    public function update(Subscriber $subscriber)
    {
        $this->validateRequest();

    	$subscriber->update(request()->all());

        return response($subscriber, 200);
    }

    public function destroy(Subscriber $subscriber)
    {
    	$subscriber->delete();

        $subscriber->fields()->delete();

        return response($subscriber, 204);
    }

    protected function validateRequest()
    {
        request()->validate([
            'name' => 'required',
            'email' => [
                'bail',
                'required',
                'email',
                'unique:subscribers',
                function ($attribute, $value, $fail) {
                    $domain = substr($value, strpos($value, '@') + 1);

                    if (! checkdnsrr($domain, "A")) {
                        $fail('The email domain must be active.');
                    }
                }
            ]
        ]);
    }
}

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

        return Subscriber::create(request()->all());
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

        return response($subscriber, 204);
    }
}

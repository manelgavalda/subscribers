<?php

namespace App\Http\Controllers;

use App\Subscriber;

class SubscriberController extends Controller
{
    public function store()
    {
    	request()->validate([
    		'name' => 'required',
    		'email' => [
    			'required',
    			'email',
		        function ($attribute, $value, $fail) {
					$domain = substr($value, strpos($value, '@') + 1);

	            	if (! checkdnsrr($domain, "A")) {
	                	$fail('The email domain must be active.');
	            	}
	        	}
	        ]
    	]);

    	return Subscriber::create(request()->all());
    }
}

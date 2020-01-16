<?php

use App\Subscriber;
use Illuminate\Database\Seeder;

class FieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	foreach (Subscriber::all() as $subscriber) {
    		factory('App\Field', 2)->create([
    			'subscriber_id' => $subscriber->id
    		]);
    	}
    }
}

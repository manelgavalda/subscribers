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
        Subscriber::all()->each(fn ($subscriber) =>
    		factory('App\Field', 2)->create(
                ['subscriber_id' => $subscriber->id]
            )
        );
    }
}

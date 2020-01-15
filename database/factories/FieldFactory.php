<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Field;
use Faker\Generator as Faker;

$factory->define(Field::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'type' => 'string',
        'value' => 'string',
        'subscriber_id' => factory('App\Subscriber')
    ];
});

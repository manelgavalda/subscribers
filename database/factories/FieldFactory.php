<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Field;
use Faker\Generator as Faker;

$factory->define(Field::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'type' => 'string',
        'value' => $faker->name,
        'subscriber_id' => factory('App\Subscriber')
    ];
});

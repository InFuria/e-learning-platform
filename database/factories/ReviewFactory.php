<?php

use Faker\Generator as Faker;
use App\Course;

$factory->define(App\Review::class, function (Faker $faker) {
    return [
        'course_id' => Course::all()->random()->id,
        'rating' => $faker->randomFloat(2, 1, 5)
    ];
});

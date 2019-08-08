<?php

use Faker\Generator as Faker;
use App\Course;

$factory->define(App\Requirement::class, function (Faker $faker) {
    return [
        'course_id' => Course::all()->random()->id,
        'requirement' => $faker->sentence,
    ];
});

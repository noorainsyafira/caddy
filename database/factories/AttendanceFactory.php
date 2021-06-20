<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Attendance;
use Faker\Generator as Faker;

$factory->define(Attendance::class, function (Faker $faker) {
    return [
        'employee_id' => 1,
        'shift' => $faker->randomElement(['morning', 'afternoon']),
        'rounds' => $faker->randomElement(['half', 'full']),
    ];
});

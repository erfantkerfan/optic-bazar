<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'mobile' => '09' . $faker->unique()->numberBetween(111111111,999999999),
        'password' => '$2y$10$k7Y4i7P4hLlJ0L4yYCMyWuhX7B8w/sRLpbUAFeSqzG6YMYpMO9OqG', // 191522
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Brand::class, function (Faker $faker) {
    return [
        'name' => $faker->lastName,
        'logo' => $faker->imageUrl(),
        'description' => $faker->paragraph(2),
    ];
});


$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'seller_id' => 1,
        'type' => $faker->numberBetween(1,2),
        'sku' => $faker->unique()->numberBetween(111111111,999999999),
        'group' => '4',
        'image' => $faker->imageUrl(),
        'price' => $faker->numberBetween(100000,1500000),
    ];
});


$factory->define(App\Lens_detail::class, function (Faker $faker) {
    return [
        'diagonal' => $faker->numberBetween(10,55),
        'curvature' => $faker->numberBetween(10,55),
        'color' => $faker->colorName,
        'number' => $faker->numberBetween(1,2),
        'consumption_period' => $faker->numberBetween(1,20),
        'abatement' => $faker->numberBetween(0,1),
        'oxygen_supply' => $faker->numberBetween(0,1),
        'thickness' => $faker->numberBetween(0,1),
    ];
});


$factory->define(App\Optical_glass_detail::class, function (Faker $faker) {
    return [
        'light_breakdown' => $faker->numberBetween(10,55),
        'anti_photo' => $faker->numberBetween(0,1),
        'bloc_troll' => $faker->numberBetween(0,1),
        'poly_break' => $faker->numberBetween(0,1),
        'color' => $faker->numberBetween(0,2),
        'hydro' => $faker->numberBetween(0,2),
        'spherical' => $faker->numberBetween(0,2),
    ];
});

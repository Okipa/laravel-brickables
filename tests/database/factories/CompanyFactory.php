<?php

use Faker\Generator as Faker;
use Okipa\LaravelBrickables\Tests\Models\Company;
use Okipa\LaravelBrickables\Tests\Models\Page;

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

$factory->define(Company::class, function (Faker $faker) {
    return ['name' => $faker->unique()->company];
});

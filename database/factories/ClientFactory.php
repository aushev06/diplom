<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        Client::ATTR_NAME    => $faker->name,
        Client::ATTR_PHONE   => '+7(978)043-87-40',
        Client::ATTR_USER_ID => 1,
    ];
});

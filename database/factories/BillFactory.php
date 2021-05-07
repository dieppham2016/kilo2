<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Bill;
use Faker\Generator as Faker;

$factory->define(Bill::class, function (Faker $faker) {
    return [
        'bill_no' => str_pad(rand(0, 9), 4, '0', STR_PAD_LEFT),
        'status_id' => rand(1, 2),
        'created_at' => now()->subMonth(rand(0, 5))->subDay(rand(0, 31)),
        'updated_at' => now(),
    ];
});

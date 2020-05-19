<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(15),// 最多15个单词
        'price' => $faker->randomNumber(2),
        'stock' => $faker->numberBetween(10, 99),
        'main_image' => '',
        'image_list' => '',
        'pc_desc' => '',
        'wap_desc' => '',
    ];
});

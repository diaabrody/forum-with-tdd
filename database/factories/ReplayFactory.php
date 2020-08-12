<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Replay;
use Faker\Generator as Faker;

$factory->define(Replay::class, function (Faker $faker) {
    return [
        'user_id'=>function(){
           return factory(\App\User::class)->create()->id ;
        } ,
        'thread_id'=>function(){
          return  factory(\App\Thread::class)->create()->id  ;
        } ,
        'body' =>$faker->paragraph
    ];
});

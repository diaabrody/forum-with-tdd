<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract  class CustomTestCase  extends TestCase
{
    use DatabaseMigrations ;

    protected function login($user = null){
        $user = $user?:factory('App\User')->create();
        $this->actingAs($user);
    }
}

<?php

namespace Tests\Feature;

use App\Activity;
use App\interceptions\Spam;
use App\Notifications\ThreadUpdated;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Tests\CustomTestCase;
use Tests\TestCase;

class SpamTest extends CustomTestCase
{
    public function setUp():void{
        Parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_detection_method_with_no_spam(){
        $spam = new Spam();
        $this->assertFalse($spam->detect('good comment'));
    }
    public function test_detection_method_with_spam(){
        $spam = new Spam();
        $this->expectException(\Exception::class);
        $spam->detect('bad comment');
    }

}

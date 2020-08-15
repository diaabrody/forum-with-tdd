<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CustomTestCase;
use Tests\TestCase;

class NotificationsTest extends CustomTestCase
{
    public function setUp():void{
        Parent::setUp();
    }

    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user(){
        $this->login();
        $thread = create('App\Thread');
        $thread->subscribe();
        $this->assertCount(0 , auth()->user()->notifications);

        $thread->addReplay([
            'user_id'=>auth()->user()->id,
            'body'=>'hi'
        ]);

        $this->assertCount(0 , auth()->user()->fresh()->notifications);


        $thread->addReplay([
            'user_id'=>create('App\User')->id,
            'body'=>'hi'
        ]);

        $this->assertCount(1 , auth()->user()->fresh()->notifications);
    }


    public function test_a_user_can_clear_a_notification(){
        $this->login();
        $thread = create('App\Thread');
        $thread->subscribe();
        $thread->addReplay([
            'user_id'=>create('App\User')->id,
            'body'=>'hi'
        ]);
        $this->assertCount(1 , auth()->user()->fresh()->unReadNotifications);
        $notification = auth()->user()->unReadNotifications->first()->id;
        $this->delete("/profiles/".auth()->user()->id."/".$notification)
            ->assertStatus(200);
        $this->assertCount(0 , auth()->user()->fresh()->unReadNotifications);
    }
}

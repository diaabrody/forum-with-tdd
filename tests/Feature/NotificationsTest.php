<?php

namespace Tests\Feature;

use App\Activity;
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

class NotificationsTest extends CustomTestCase
{
    public function setUp():void{
        Parent::setUp();
        $this->login();
    }

    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user(){
        Notification::fake();
        $thread = create('App\Thread');
        $thread->subscribe();

        $thread->addReplay([
            'user_id'=>auth()->user()->id,
            'body'=>'hi'
        ]);
        Notification::assertNothingSent();

        $this->assertCount(0 , auth()->user()->fresh()->notifications);


        $thread->addReplay([
            'user_id'=>create('App\User')->id,
            'body'=>'hi'
        ]);

        Notification::assertSentTo(auth()->user() , ThreadUpdated::class);
    }


    public function test_a_user_can_clear_a_notification(){
        create(DatabaseNotification::class);
        tap(auth()->user() , function ($user){
            $notification = $user->unReadNotifications->first()->id;
            $this->delete("/profiles/".$user->name."/notifications/".$notification)
                ->assertStatus(200);
            $this->assertCount(0 , $user->fresh()->unReadNotifications);
        });

    }

    public function test_a_user_can_read_his_notification(){
        create(DatabaseNotification::class);
        $response= $this->getJson("/profiles/".auth()->user()->name ."/notifications")
            ->json();
        $this->assertCount(1 , $response);
    }
}

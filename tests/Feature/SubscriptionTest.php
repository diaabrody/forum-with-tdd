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

class SubscriptionTest extends CustomTestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function SetUp(): void
    {
        Parent::setUp();
        $this->withExceptionHandling();

    }


    public function test_auth_user_can_subscribe_to_thread(){
        $this->login();
        $thread=create('App\Thread');
        $this->post($thread->path().'/subscriptions')
        ->assertStatus(201);

        $this->assertDatabaseHas('thread_subscriptions' , [
            'user_id'=>auth()->id(),
            'thread_id'=>$thread->id
        ]);

    }

    public function test_auth_user_can_unsubscribe_to_thread(){
        $this->login();
        $thread=create('App\Thread');
        $this->post($thread->path().'/subscriptions')
            ->assertStatus(201);

        $sub = [
            'user_id'=>auth()->id(),
            'thread_id'=>$thread->id
        ];
        $this->assertDatabaseHas('thread_subscriptions' , $sub);
        $this->delete($thread->path().'/subscriptions')
            ->assertStatus(200);
        $this->assertDatabaseMissing('thread_subscriptions' , $sub);
    }

    public function test_guest_cannot_subscribe_to_thread(){
        $thread=create('App\Thread');
        $this->post($thread->path().'/subscriptions')
            ->assertRedirect('/login');
    }
    public function test_guest_cannot_unsubscribe_to_thread(){
        $thread=create('App\Thread');
        $this->delete($thread->path().'/subscriptions')
            ->assertRedirect('/login');
    }

}

<?php

namespace Tests\Feature;

use App\Replay;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use phpDocumentor\Reflection\Types\Parent_;
use Tests\CustomTestCase;
use Tests\TestCase;

class ParticipateInThreadsTest extends CustomTestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function SetUp():void {
        Parent::setUp();

    }
    public function test_unauthenticated_user_can_not_participate_in_forum_threads()
    {
        $this->withExceptionHandling();
        $thread=create(Thread::class);
        $this->post("{$thread->path()}/replies", [
            'body'=>'gpppp'
        ])->assertRedirect('/login');
    }

    public function test_an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->be($user=factory(User::class)->create());
        $thread=create(Thread::class);
        $replay=make(Replay::class);
        $this->post("{$thread->path()}/replies" , $replay->toArray());
        $this->get($thread->path())->assertSee($replay->body);
    }

    public function test_a_replay_require_body(){
        $this->be($user=factory(User::class)->create());
        $thread=create(Thread::class);
        $replay=make(Replay::class , ['body'=>null]);
        $this->post("{$thread->path()}/replies" , $replay->toArray())
            ->assertSessionHasErrors(['body']);
    }
}

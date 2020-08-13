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
        $replay=create(Replay::class);
        $this->post("{$thread->path()}/replies" , $replay->toArray());
        $this->assertDatabaseHas('replays' , ['id'=>$replay->id] );

        $this->assertEquals(1,$replay->thread->replies_count);
    }

    public function test_a_replay_require_body(){
        $this->be($user=factory(User::class)->create());
        $thread=create(Thread::class);
        $replay=make(Replay::class , ['body'=>null]);
        $this->post("{$thread->path()}/replies" , $replay->toArray())
            ->assertSessionHasErrors(['body']);
    }

    public  function test_authorize_user_can_delete_their_replies(){
        $this->login();
        $replay=create('App\Replay'  , ['user_id' =>auth()->user()->id]);
        $this->delete("replay/{$replay->id}");
        $this->assertDatabaseMissing('replays' , ['id'=>$replay->id]);
        $this->assertEquals(0,$replay->thread->refresh()->replies_count);
    }

    public  function test_un_authorize_user_cannot_delete_replies(){
        $this->login();
        $replay=create('App\Replay');
        $this->delete("replay/{$replay->id}")
            ->assertStatus(403);
    }

    public  function test_authorize_user_can_update_their_replies(){
        $this->login();
        $replay=create('App\Replay'  , ['user_id' =>auth()->user()->id]);
        $this->patch("/replay/{$replay->id}" , ['body'=>'test body']);
        $this->assertDatabaseHas('replays' , ['id'=>$replay->id , 'body'=>'test body']);
    }
    public  function test_unauthorize_user_cannot_update_replies(){
        $this->login();
        $replay=create('App\Replay');
        $this->patch("/replay/{$replay->id}" , ['body'=>'test body'])
            ->assertStatus(403);
    }


}

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

class CreateThreadTest extends CustomTestCase
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
    public function SetUp():void {
        Parent::setUp();
        $this->withExceptionHandling();

    }

    public function test_unauthenticated_user_can_not_see_create_thread_page(){
        $this->get('/threads/create')
            ->assertRedirect('/login');
        $thread = make(Thread::class);
        $this->post("/threads" , $thread->toArray())
            ->assertRedirect('/login');
    }
    public   function test_authenticated_user_can_create_thread()
    {
    //        $this->login();
    //        $thread = make(Thread::class);
    //        $response=$this->post("/threads" , $thread->toArray());
    //        dd($response->headers->has('Location'));
    //        $this->get('')
    //            ->assertStatus(200)
    //            ->assertSee($thread->title);
    }
    public function test_a_thread_requires(){
        $this->login();
        $this->post("/threads" ,[])
            ->assertSessionHasErrors(['title' , 'body' , 'channel_id']);
    }
    public function test_a_thread_require_valid_exists_channel_id(){
        $this->login();
        $channel=create('App\Channel');
        $thread= make('App\Thread' , ['channel_id'=>23]);
        $this->post("/threads" ,$thread->toArray())
            ->assertSessionHasErrors(['channel_id']);
    }

    public function test_authorize_user_can_delete_his_thread(){
       $this->withoutExceptionHandling();
       $this->login();
       $thread= create('App\Thread' ,['user_id'=>auth()->id()]);
       $replay= create('App\Replay' ,["thread_id"=>$thread->id]);
       $this->json('DELETE' , $thread->path());
       $this->assertDatabaseMissing('threads' ,['id'=>$thread->id]);
       $this->assertDatabaseMissing('replays' , ['id'=>$replay->id]);
       $this->assertEquals(0 ,count(Activity::all()) );
    }

    public function test_authorize_user_cannot_delete_thread(){
        $thread= create('App\Thread');
        $this->delete( $thread->path())
            ->assertRedirect('/login');

        $this->login();
        $this->delete( $thread->path())
            ->assertStatus(403);
    }



}

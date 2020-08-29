<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CustomTestCase;
use Tests\TestCase;

class ReadThreadsTest extends CustomTestCase{
    protected $thread;
    public function setUp():void{
        Parent::setUp();
        $this->thread = create('App\Thread');
    }


    public function test_a_user_can_view_all_threads(){
        $this->get('/threads')->assertSee( $this->thread->name);
    }

    public  function test_a_user_can_read_a_single_thread(){
        $this->get("{$this->thread->path()}")->assertSee($this->thread->title);
    }

    public  function test_user_can_filter_threads_according_to_channel(){
        $thread = create('App\Thread');
        $this->get("threads/{$thread->channel->slug}")
            ->assertStatus(200)
            ->assertSee(  $thread->title);
    }
    public function test_user_can_filter_his_threads(){
        $this->login($user = create('App\User' , ['name'=>'johnDoe']));
        $thread=create('App\Thread');
        $threadByJohnDoe=create('App\Thread' , ['user_id'=>$user->id]);
        $this->get('/threads?by=johnDoe')
            ->assertStatus(200)
            ->assertSee($threadByJohnDoe->title)
            ->assertDontSee($thread->title);

    }
    public function test_user_can_filter_threads_by_popularity(){
        $threadWith3Replaies =  create('App\Thread');
        create('App\Replay' , ['thread_id' =>$threadWith3Replaies->id] , 3);
        $threadWith5Replaies =  create('App\Thread');
        create('App\Replay' , ['thread_id' =>$threadWith5Replaies->id] , 5);
        $threadWith1Replay = $this->thread;
        $reponse=$this->getJson('threads?popular=1')->json();
        $this->assertEquals([5,3,0],array_column($reponse['data'] , 'replies_count'));
    }

    function test_a_user_can_filter_threads_by_those_that_are_unanswered(){
        $thread=create('App\Thread');
        $replay=create('App\Replay' , ['thread_id'=>$thread->id]);
        $res=$this->getJson('threads?unanswered=1')->json();
        $this->assertCount(1 , $res['data']);
    }



}

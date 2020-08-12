<?php


namespace Tests\Unit;


use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CustomTestCase;
use Tests\TestCase;

class ThreadTest extends CustomTestCase
{
    protected $thread;
    public function setUp(): void
    {
        parent::setUp();
        $this->thread = create(Thread::class);
    }
    public function test_a_thread_has_replies(){
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection' ,
            $this->thread->replies);
    }
    public function test_a_thread_has_a_creator(){
        $this->assertInstanceOf(User::class , $this->thread->creator);
    }
    public function test_a_thread_has_a_channel(){
       $this->assertInstanceOf( 'App\Channel',$this->thread->channel);
    }

    public function test_a_path_thread(){
        $thread=make('App\Thread');
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}"
            , $thread->path());
    }
    public function test_add_replay(){
        $this->thread->addReplay(
            [
                'body' => 'Foobar',
                'user_id' => 1
            ]
        );
        $this->assertCount(1 , $this->thread->replies);
    }

    public  function test_channel_has_threads(){
        $channel=create('App\Channel');
        $thread=create('App\Thread' , ['channel_id'=>$channel->id]);
       $this->assertTrue($channel->threads->contains($thread));
    }




}

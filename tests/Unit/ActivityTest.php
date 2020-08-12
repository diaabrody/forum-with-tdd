<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CustomTestCase;
use Tests\TestCase;

class ActivityTest extends CustomTestCase
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
    public function test_create_Activity_for_thread(){
        $this->login();
        $thread= create('App\Thread');
        $this->assertDatabaseHas('activities', [
            'user_id'=>auth()->id() ,
            'type'=>'created_thread',
            'subject_type'=>get_class($thread),
            'subject_id'=>$thread->id
        ]);
    }
    public function test_create_Activity_for_reply(){
        $this->login();
        $replay= create('App\Replay');
        $this->assertDatabaseHas('activities' , [
            'user_id'=>auth()->id() ,
            'type'=>'created_replay',
            'subject_type'=>get_class($replay),
            'subject_id'=>$replay->id
        ]);
    }
    public  function  test_a_activity_has_subject(){
        $this->login();
        $thread = create('App\Thread' , [
            'user_id'=>auth()->id()
        ]);
        $act=Activity::first();

        $this->assertEquals($act->subject->id , $thread->id);
    }

    public  function  test_user_model_has_activites(){
        $this->login();
        $thread = create('App\Thread' , [
            'user_id'=>auth()->id()
        ]);
        $this->assertEquals('App\Activity' ,
            get_class(auth()->user()->activities->first()));
    }

    public function test_feeds_on_activity(){
        $this->login();
        $thread = create('App\Thread' , [
            'user_id'=>auth()->id()
        ] ,2);
        Activity::first()->update('created_at' , Carbon::now()->subWeek());
       $feed= Activity::feed(auth()->user());
//       $this->assertTrue();

    }
}

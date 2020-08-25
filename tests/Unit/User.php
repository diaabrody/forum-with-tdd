<?php


namespace Tests\Unit;


use Tests\CustomTestCase;

class User extends CustomTestCase
{
    public function setUp():void{
        Parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function a_user_can_fetch_their_most_recent_reply(){
        $this->login();
        $thread = create('App\Thread');
        $reply= $thread->addReplay([
            'body' => 'nope',
            'user_id'=>auth()->id()
        ]);
        $this->assertEquals($reply->id , auth()->user()->lastestReply->id);
    }

}

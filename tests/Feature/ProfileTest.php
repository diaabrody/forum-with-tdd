<?php
namespace Tests\Feature;

use App\Activity;
use Tests\CustomTestCase;

class ProfileTest extends CustomTestCase{
    public function setUp():void{
        Parent::setUp();
        $this->withoutExceptionHandling();
    }
    public function test_user_can_see_profile_page()
    {
        $this->login();
        $thread = create('App\Thread' , ['user_id'=>auth()->id()]);
        $this->get("/profiles/".auth()->user()->name)
            ->assertStatus(200)
            ->assertSee(Activity::first()->subject->body);

    }
}

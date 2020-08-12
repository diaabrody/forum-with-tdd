<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CustomTestCase;
use Tests\TestCase;

class Favourite extends CustomTestCase{
    public function setUp():void{
        Parent::setUp();
    }
    public function test_auth_user_can_favourite_replay(){
        $this->login();
        $replay = create('App\Replay');
        $this->post("/replies/{$replay->id}/favourites");
        $this->assertCount(1  , $replay->favourites);
    }
    public function test_guest_cannot_favourite_replay(){
        $replay = create('App\Replay');
        $this->post("/replies/{$replay->id}/favourites")
            ->assertRedirect('/login');
    }
    public function test_auth_user_cannot_favourite_twice_to_the_same_replay(){
        $this->login();
        $replay = create('App\Replay');
        $this->post("/replies/{$replay->id}/favourites");
        $this->post("/replies/{$replay->id}/favourites");
        $this->assertCount(1  , $replay->favourites);

    }
    public function test_guest_cannot_unfavourite_replay(){
        $replay = create('App\Replay');
        $this->delete("/replies/{$replay->id}/favourites")
            ->assertRedirect('/login');
    }
    public function test_auth_user_cannot_unfavourite_replay(){
        $this->login();
        $replay = create('App\Replay');
        $replay->favourite();
        $this->delete("/replies/{$replay->id}/favourites")
            ->assertStatus(200);
        $this->assertEquals(count($replay->favourites) , 0);
    }
}

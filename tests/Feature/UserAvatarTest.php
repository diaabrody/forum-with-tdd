<?php


namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CustomTestCase;
use Tests\TestCase;

class UserAvatarTest extends CustomTestCase
{

    public function SetUp(): void
    {
        Parent::setUp();
        $this->withExceptionHandling();
    }

    public  function test_un_auth_user_cannot_post_avatar(){
        $this->postJson('/api/users/1/avatar' , [
            'avatar'=>'invalid-image'
        ])->assertStatus(401);
    }
    public  function test_a_valid_avatar_should_be_provided(){
        $this->login();
        $this->postJson('/api/users/1/avatar' , [
           'avatar'=>'invalid-image'
        ])->assertStatus(422);
    }

    public function test_user_can_add_user_avatar(){
        $this->login();
        Storage::fake('public');
        $this->postJson("/api/users/".auth()->id()."/avatar" ,[
            'avatar'=>   $file = UploadedFile::fake()->image('avatar.png')
        ]);
        $path = 'avatars/'.$file->hashName();
        $this->assertEquals(auth()->user()->avatar_path, Storage::url($path));
        Storage::disk('public')->assertExists($path);
    }
    public  function  test_user_can_decide_his_avatar(){
        $user=$this->login();
        $this->assertEquals(Storage::url('avatars/default.png') , $user->avatar_path);
        $user->avatar_path = "avatars/test.png";
        $this->assertEquals(  Storage::url('avatars/test.png') , $user->avatar_path);
    }


}

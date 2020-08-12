<?php


namespace Tests\Unit;
use App\Replay;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CustomTestCase;
use Tests\TestCase;

class ReplayTest extends CustomTestCase
{

    public function test_replay_has_owner(){
       $replay= create(Replay::class);
       self::assertInstanceOf(User::class , $replay->owner);
    }
}

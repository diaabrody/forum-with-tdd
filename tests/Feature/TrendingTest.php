<?php


namespace Tests\Feature;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\CustomTestCase;

class TrendingTest extends CustomTestCase
{

    public function SetUp(): void
    {
        Parent::setUp();
        $this->withExceptionHandling();
    }

    public  function test_trending_threads(){
        self::assertEmpty(Redis::zrevrange('trending_threads' ,  0 ,-1));
        $thread=create('App\Thread');
        $this->get($thread->path());
        self::assertCount(1,Redis::zrevrange('trending_threads' ,  0 ,-1));
    }
}

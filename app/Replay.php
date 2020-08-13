<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Replay extends Model
{
    use Favourable;
    use RecordActivity;
    protected $guarded = [];
    protected $with =['owner' , 'thread'];
    protected $appends = ['isFavorited' , 'favouritesCount'];

    public static function  boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::addGlobalScope('favourites' , function ($builder){
            $builder->with('favourites');
        });
        self::addGlobalScope('favouritesCount' , function ($builder){
            $builder->withCount('favourites');
        });
        static::created(function($reply){
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply){
            if ($reply->thread)
                $reply->thread->decrement('replies_count');
        });
    }

    //
    public function owner(){
        return $this->belongsTo(User::class , 'user_id');
    }
    public function thread(){
        return $this->belongsTo(Thread::class);
    }

    public function path(){
        return $this->thread->path() . "#$this->id";
    }
    public function getFavouritesCountAttribute(){
        return count($this->favourites);
    }

}

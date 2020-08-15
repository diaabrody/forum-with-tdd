<?php

namespace App;

use App\Filters\ThreadFilters;
use App\Notifications\ThreadUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordActivity;
    protected $guarded = [];
    //
    protected $with = ['channel'];
    protected $appends=['isSubscribedTo'];

    public static function boot()
    {
        parent::boot();
//        static::addGlobalScope('repliesCount', function ($builder) {
//            $builder->withCount('replies');
//        });
        static::deleted(function ($thread){
            $thread->replies->each->delete();
        });

    }

    public static function getActivityActions(){
        return ['created'];
    }


    public function replies()
    {
        return $this->hasMany(Replay::class , 'thread_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReplay($replay)
    {
       $replay= $this->replies()->create($replay);

       $this->subscriptions->filter(function ($sub) use($replay){
           return $replay->user_id !=$sub->user_id;
       })->each(function ($sub)use($replay){
           $sub->user->notify(new ThreadUpdated($replay  , $this));
       });

       return $replay;
    }

    public function subscriptions()
    {
        return $this->hasMany('App\ThreadSubscription');
    }

    public function subscribe($userId = null){
        $this->subscriptions()->create([
            "user_id"=>$userId?:auth()->id()
        ]);
    }

    public function unsubscribe($userId = null){
        $this->subscriptions()
            ->where('user_id' , $userId?:auth()->id())
            ->delete();
    }
    public function getIsSubscribedToAttribute(){
       return $this->subscriptions()
            ->where('user_id' , auth()->id())
            ->exists();
    }

    /**
     *
     * Apply all relevant thread filters.
     *
     * @param Builder $query
     * @param ThreadFilters $filters
     * @return Builder
     */

    public function scopeFilter($query, ThreadFilters $filter)
    {
        return $filter->apply($query);
    }

}

<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordActivity;
    protected $guarded = [];
    //
    protected $with = ['channel'];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('repliesCount', function ($builder) {
            $builder->withCount('replies');
        });
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
       return $this->replies()->create($replay);
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

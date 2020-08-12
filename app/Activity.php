<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //
    protected $guarded=[];

    public function subject(){
        return $this->morphTo();
    }

    /**
     * @param User $user
     * @param int $take
     * @return mixed
     */
    public static function feed(User $user , $take=50){
        return Activity::where('user_id' , $user->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('j F Y');
            });
    }
}

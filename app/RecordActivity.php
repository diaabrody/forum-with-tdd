<?php


namespace App;


trait RecordActivity
{
    public static function bootRecordActivity(){
        if (auth()->guest()) return;
        foreach (static::getActivityActions() as $activity ){
            static::$activity(function ($model) use ($activity) {
                $model->recordActivityModel($activity);
            });
        }

        static::deleted(function ($model){
            $model->activity()->delete();
        });
    }
    public function recordActivityModel($event){
       $this->activity()->create([
            'user_id' =>  auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }
    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
    public static function getActivityActions(){
        return ['created'];
    }
    public function activity(){
        return $this->morphMany('App\Activity' ,'subject');
    }
}

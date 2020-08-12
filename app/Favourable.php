<?php


namespace App;


trait Favourable
{
    public function favourites(){
        return $this->morphMany(Favourite::class , 'favorited');
    }
    public function favourite($user=null){
        $user=$user?$user->id:auth()->id();
        if (!$this->favourites()->where('user_id' ,$user )->exists()){
            $this->favourites()->create([
                'user_id' => $user
            ]);
        }else{
            $this->favourites()->where('user_id' ,$user )
                ->get()
                ->each
                ->delete();
        }
    }
    public function isfavourited(){
        return !! $this->favourites->where('user_id' , auth()->id())->count();
    }

    public function getIsFavoritedAttribute(){
        return  $this->isfavourited();
    }

    public static  function  bootFavourable(){
        static::deleted(function ($model){
            $model->favourites()->get()->each->delete();
        });
    }
}

<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function threads(){
        return $this->hasMany(Thread::class);
    }
    public function isAdmin(){
        return $this->id == 52;
    }

    public function activities(){
        return $this->hasMany(Activity::class);
    }
    public function getNotifications($user=null){
        $user= $user?:auth()->user();
        return $user->notifications;
    }

    public function markNotificationAsRead($notification,$user=null){
        $user= $user?:auth()->user();
        return $user->notifications()->findOrFail($notification)->markAsRead();
    }

    public function lastestReply(){
        return $this->hasOne('App\Replay')->latest();
    }

    public function getAvatarPathAttribute($avatar){
       return Storage::url($avatar ?:'avatars/default.png') ;
    }
}

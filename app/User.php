<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public function polls(){
        return $this->hasMany('App\Poll');
    }

    public function poll_options(){
        return $this->hasMany('App\PollOption');
    }

    public function votes(){
        return $this->hasMany('App\Vote');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }
    public function following() {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')->withTimestamps();
    }
    public function followers() {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }
    public function isFollowing($user_id){
        return !! $this->following()->where('following_id', $user_id)->count();
    }
    public function isFollowed($user_id){
        return !! $this->followers()->where('following_id', $user_id)->count();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','slug','description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

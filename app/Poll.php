<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function poll_options(){
        return $this->hasMany('App\PollOption');
    }
    public function comments(){
        return $this->hasMany('App\Comment');
    }
    protected $fillable = [
        'title','user_id','youtube_url','twitter_url','instagram_url'
    ];
}

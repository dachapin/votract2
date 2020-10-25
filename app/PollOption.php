<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    public function poll(){
        return $this->belongsTo('App\Poll');
    }
    public function votes(){
        return $this->hasMany('App\Vote');
    }
    protected $fillable = [
        'content','poll_id'
    ];
}

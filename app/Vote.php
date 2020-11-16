<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public function poll_option(){
        return $this->belongsTo('App\PollOption');
    }
    public function users(){
        return $this->belongsTo('App\User');
    }
    public function polls(){
        return $this->belongsTo('App\Poll');
    }
    protected $fillable = [
        'poll_id','poll_option_id','user_id'
    ];
}

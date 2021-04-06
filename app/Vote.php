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
        return $this->belongsTo('App\Poll')->orderBy('created_at','DESC');
    }
    protected $fillable = [
        'poll_id','poll_option_id','user_id','ip_address'
    ];
}

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

    protected $fillable = [
        'poll_option_id'
    ];
}

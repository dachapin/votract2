<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function poll(){
        return $this->belongsTo('App\Poll');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    protected $fillable = [
        'content','user_id','poll_id'
    ];
}

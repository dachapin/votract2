<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poll;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $polls = Poll::orderBy( 'created_at','DESC')->paginate(10);
        $user = User::find(auth()->id());
        $vote_polls = [];
        $vote_poll_options = [];
        if(auth()->check()){
            for($i = 0; $i < count($user->votes); $i++ ){
                $vote_polls[] = $user->votes[$i]['poll_id'];
                $vote_poll_options[] = $user->votes[$i]['poll_option_id'];
            }
        }
        return view('poll.index',[
            'polls' => $polls,
            'user' => $user,
            'vote_polls' => $vote_polls,
            'vote_poll_options' => $vote_poll_options
        ]);
    }
}

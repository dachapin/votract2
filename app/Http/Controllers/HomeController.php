<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poll;

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
        return view('poll.index',[
            'polls' => $polls
        ]);
    }
}

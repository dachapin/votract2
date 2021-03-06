<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poll;
use App\PollOption;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;

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
        SEOMeta::setTitle('ASQUE: Online Poll and Voting Social Media');
        SEOMeta::setDescription('ASQUE.com is an online poll and voting site.You can ask anything useing ASQUE poll system');

        $polls = Poll::orderBy( 'created_at','DESC')->paginate(10);
        $voted_polls_by_user = [];
        $voted_poll_option_ids_by_user = [];
        $followings = '';
        if(auth()->check()){
            $user = User::find(auth()->id());
            $followings = $user->following()->get();
            for($i = 0; $i < count($user->votes); $i++ ){
                $voted_polls_by_user[] = $user->votes[$i]['poll_id'];
                $voted_poll_option_ids_by_user[] = $user->votes[$i]['poll_option_id'];
            }
        }
        return view('poll.index',[
            'polls' => $polls,
            'voted_polls_by_user' => $voted_polls_by_user,
            'voted_poll_option_ids_by_user' => $voted_poll_option_ids_by_user,
            'followings' => $followings
        ]);
    }

    public function history(Request $request)
    {
        if(auth()->check()){
            return redirect('/');
        }
        $sessions = $request->session()->all();
        $votedPolls = [];

        if(session()->has('voted')){
            foreach($sessions['voted'] as $session){
                $pollOtion = PollOption::find($session);
                $votedPolls[] = $pollOtion->poll;
            }
        }
        $polls = [];
        if(session()->has('posted')){
            foreach($sessions['posted'] as $post){
                $poll = Poll::find($post);
                $polls[] = $poll;
            }
        }
        $voted_polls_by_user = [];
        $voted_poll_option_ids_by_user = [];
        return view('history',[
            'polls' => $polls,
            'votedPolls' => $votedPolls,
            'voted_polls_by_user' => $voted_polls_by_user,
            'voted_poll_option_ids_by_user' => $voted_poll_option_ids_by_user
        ]);
    }
}

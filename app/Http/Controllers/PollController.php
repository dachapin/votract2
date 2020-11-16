<?php

namespace App\Http\Controllers;

use App\Poll;
use App\PollOption;
use App\Vote;
use App\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $polls = Poll::orderBy( 'created_at','DESC')->paginate(10);
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
            'vote_polls' => $vote_polls,
            'vote_poll_options' => $vote_poll_options
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('poll.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $poll_options = array_filter($request->poll_option,'strlen');
        if(count($poll_options) < 2){
            $request->validate([
                'title' => 'required|min:3',
                'poll_option.*' => 'required',
                'image' => 'mimes:jpeg,bmp,png'
            ]);
            return redirect()->back()->withInput();
        }else{
            $request->validate([
                'title' => 'required|min:3',
                'image' => 'mimes:jpeg,bmp,png,gif,jpg|max:20000'
            ]);
        }
        $poll = new Poll([
            'title' => $request->title,
            'youtube_url' => $request->youtube_url,
            'twitter_url' => $request->twitter_url,
            'instagram_url' => $request->instagram_url,
            'user_id' => auth()->check() ? auth()->id() : null
        ]);
        $poll->save();
        foreach($poll_options as $poll_option){
            $poll_option = new PollOption([
                'content' => $poll_option,
                'poll_id' => $poll->id
            ]);
            $poll_option->save();
        }
        if($request->image){
            $this->saveImages($request->image,$poll->id);
        }
        return redirect('/poll');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function show(Poll $poll)
    {
        $user = User::find(auth()->id());
        $vote_polls = [];
        $vote_poll_options = [];
        if(auth()->check()){
            for($i = 0; $i < count($user->votes); $i++ ){
                $vote_polls[] = $user->votes[$i]['poll_id'];
                $vote_poll_options[] = $user->votes[$i]['poll_option_id'];
            }
        }
        return view('poll.show',[
            'poll' => $poll,
            'user' => $user,
            'vote_polls' => $vote_polls,
            'vote_poll_options' => $vote_poll_options
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function edit(Poll $poll)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Poll $poll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poll $poll)
    {
        $poll->delete();
        return redirect('/');
    }

    public function saveImages($image_input,$poll_id){
        $image = Image::make($image_input);
        if($image->width() > $image->height() ){
            $image->widen(1200)
                ->save(public_path().'/img/polls/'. $poll_id . '_large.jpg',50)
                    ->widen(400)->pixelate(12)
                    ->save(public_path().'/img/polls/'. $poll_id . '_pixelated.jpg',50);
            $image = Image::make($image_input);
                $image->widen(150)
                    ->save(public_path().'/img/polls/'. $poll_id . '_thumb.jpg',50);
        }else{
            $image->heighten(900)
                ->save(public_path().'/img/polls/'. $poll_id . '_large.jpg',50)
                    ->heighten(400)->pixelate(12)
                    ->save(public_path().'/img/polls/'. $poll_id . '_pixelated.jpg',50);
            $image = Image::make($image_input);
                $image->heighten(150)
                    ->save(public_path().'/img/polls/'. $poll_id . '_thumb.jpg',50);
        }
    }
}

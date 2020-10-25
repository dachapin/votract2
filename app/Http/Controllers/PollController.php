<?php

namespace App\Http\Controllers;

use App\Poll;
use App\PollOption;
use App\Vote;
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
        return view('poll.index',[
            'polls' => $polls
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
            $image = Image::make($request->image);
            if($image->width() > $image->height() ){
                $image->widen(1200)
                    ->save(public_path().'/img/polls/'. $poll->id . '_large.jpg',50)
                        ->widen(400)->pixelate(12)
                        ->save(public_path().'/img/polls/'. $poll->id . '_pixelated.jpg',50);
            $image = Image::make($request->image);
                $image->widen(150)
                    ->save(public_path().'/img/polls/'. $poll->id . '_thumb.jpg',50);
            }else{
                dd('portrait');
            }
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
        $poll_options = $poll->poll_options;
        return view('poll.show',[
            'poll' => $poll,
            'poll_options' => $poll_options
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
        //
    }
}

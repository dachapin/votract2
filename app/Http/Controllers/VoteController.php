<?php

namespace App\Http\Controllers;

use App\Vote;
use App\PollOption;
use App\Poll;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'poll_option_id' => 'required',
        ]);

        $pollOption = PollOption::find($request->poll_option_id);
        $poll_id = $pollOption->poll->id;

        if(!auth()->check()){
            if(!isset(session('voted')['poll_id_'.$poll_id])){
                $request->session()->put('voted.poll_id_'.$poll_id, intval($request->poll_option_id));
            }else{
                return redirect()->back();
            }
        }

        if(auth()->check()){
            $vote = Vote::where([
                'user_id' => auth()->id(),
                'poll_id' => $poll_id
            ])->get();
            if(count($vote) > 0){
                return redirect()->back();
            }
        }
        $vote = new Vote([
            'poll_id' => $poll_id,
            'poll_option_id' => $request->poll_option_id,
            'user_id' => auth()->check() ? auth()->id() : null
        ]);
        $vote->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        //
    }
}

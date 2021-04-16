<?php

namespace App\Http\Controllers;
use Artesaos\SEOTools\Facades\SEOMeta;

use App\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class UserController extends Controller
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        SEOMeta::setTitle($user->name.' | ASQUE');

        $polls = $user->polls;
        $votedObjects = $user->votes;
        $voted_polls_by_user = [];
        $voted_poll_option_ids_by_user = [];
        if(auth()->check()){
            for($i = 0; $i < count($user->votes); $i++ ){
                $voted_polls_by_user[] = $user->votes[$i]['poll_id'];
                $voted_poll_option_ids_by_user[] = $user->votes[$i]['poll_option_id'];
            }
        }
        return view('user.show',[
            'user'=>$user,
            'polls' => $polls,
            'votedObjects' => $votedObjects,
            'voted_polls_by_user' => $voted_polls_by_user,
            'voted_poll_option_ids_by_user' => $voted_poll_option_ids_by_user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if(!auth()->check()){
            return redirect('/');
        }
        return view('user.edit',[
            'user'=>$user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if(!auth()->check()){
            return redirect('/');
        }
        if($user->slug !== $request->slug){
            $request->validate([
                'image' => 'mimes:jpeg,bmp,png,gif,jpg',
                'slug' => 'max:255|unique:users',
                'description' => 'max:160'
            ]);
        }else{
            $request->validate([
                'image' => 'mimes:jpeg,bmp,png,gif,jpg',
                'slug' => 'max:255',
                'description' => 'max:160'
            ]);
        }
        if($request->image){
            $this->saveImages($request->image,$user->id);
        }

        $user->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);
        return redirect('/user/'.$user->id)->with([
            'message_success'=> 'Your user profile was updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(!auth()->check()){
            return redirect('/');
        }
        $user->delete();
        return redirect('/');
    }

    public function saveImages($imageInput,$user_id){
        $image = Image::make($imageInput);
        if ($image->width() > $image->height()){
            //Landscape
            $image->widen(800)
                ->save(public_path() . '/img/users/' . $user_id . '_large.jpg')
                    ->widen(300)->pixelate(12)
                    ->save(public_path() . '/img/users/' . $user_id . '_pixelated.jpg');
            $image = Image::make($imageInput);
            $image->widen(60)
                ->save(public_path() . '/img/users/' . $user_id . '_thumb.jpg');
        }else{//Portait
            $image->heighten(300)
                ->save(public_path() . '/img/users/' . $user_id . '_large.jpg')
                    ->heighten(300)->pixelate(12)
                    ->save(public_path() . '/img/users/' . $user_id . '_pixelated.jpg');
            $image = Image::make($imageInput);
            $image->heighten(60)
                ->save(public_path() . '/img/users/' . $user_id . '_thumb.jpg');
        }
    }
    public function deleteImages($user_id){
        if(file_exists(public_path() . '/img/users/' . $user_id . '_large.jpg')){
            unlink(public_path() . '/img/users/' . $user_id . '_large.jpg');
        }
        if(file_exists(public_path() . '/img/users/' . $user_id . '_thumb.jpg')){
            unlink(public_path() . '/img/users/' . $user_id . '_thumb.jpg');
        }
        if(file_exists(public_path() . '/img/users/' . $user_id . '_pixelated.jpg')){
            unlink(public_path() . '/img/users/' . $user_id . '_pixelated.jpg');
        }
        return back()->with([
            'message_success'=> 'The image was deleted'
        ]);
    }
    public function follow(User $user){
        $logged_in_user = auth()->user();
        $logged_in_user->following()->attach($user->id);
        return back();
    }
    public function unfollow(User $user){
        $logged_in_user = auth()->user();
        $logged_in_user->following()->detach($user->id);
        return back();
    }
    public function following(User $user){
        $followings = $user->following()->get();
        return view('user.following',[
            'user'=>$user,
            'followings'=>$followings,
        ]);
    }
    public function followers(User $user){
        $followers = $user->followers()->get();
        return view('user.followers',[
            'user'=>$user,
            'followers'=>$followers,
        ]);
    }
}

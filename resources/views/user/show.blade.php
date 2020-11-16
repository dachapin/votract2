@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    @if( auth()->id() ===  $user->id )
                        <div class="col-12 mb-2">
                            <a href="{{ url('/user/'. auth()->id(). '/edit' ) }}" class="float-right">Edit</a>
                        </div>
                    @endauth
                    @if(file_exists('img/users/'. $user->id . '_large.jpg' ))
                        <div class="mb-2">
                            <p>
                                <a href="{{ url('') }}/img/users/{{$user->id}}_large.jpg" class="profile-image">
                                    <img class="circle" src="{{ url('') }}/img/users/{{$user->id}}_large.jpg" alt="">
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="mb-2">
                            <p>
                                <a href="{{ url('') }}/img/users/default.jpg" class="profile-image">
                                    <img class="circle" src="{{ url('') }}/img/users/default.png" alt="">
                                </a>
                            </p>
                        </div>
                    @endif
                    <b>{{ $user->name }}</b>
                    <p class="mt-2">Email: {{ $user->email }}</p>
                    <p class="mt-2">Username: {{ $user->slug }}</p>
                    <p class="mt-2">Description: {{ $user->description }}</p>
                    @auth
                        @if(auth()->id() !==  $user->id )
                            @if(auth()->user()->isFollowing($user->id))
                                <form action="{{ url('') }}/user/{{ $user->id }}/unfollow" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="submit" value="Unfollow" class="btn btn-primary" >
                                </form>
                            @else
                                <form action="{{ url('') }}/user/{{ $user->id }}/follow" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="submit" value="Follow" class="btn btn-primary" >
                                </form>
                            @endif
                        @endif
                    @else
                        <form action="{{ url('') }}/user/{{ $user->id }}/follow" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="submit" value="Follow" class="btn btn-primary" disabled>
                        </form>
                    @endauth
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ url('') }}/user/{{ $user->id }}/following">Following</a>
                            {{ $user->following()->get()->count() }}
                        </div>
                        <div class="col-6">
                            <a href="{{ url('') }}/user/{{ $user->id }}/followers">Followers</a>
                            {{ $user->followers()->get()->count() }}
                        </div>
                    </div>

                </div>
            </div>
            @foreach($polls as $poll)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <time class="float-right">{{ $poll->created_at }}</time>
                            </div>
                        </div>
                        <p class="mb-0"><a href="{{ url('/poll/'. $poll->id ) }}">{{ $poll->title }}</a></p>
                        @if(file_exists('img/polls/'. $poll->id . '_large.jpg' ))
                            <a href="{{ url('/poll/'. $poll->id ) }}">
                                <img class="img-fluid" src="{{ url('/') }}/img/polls/{{$poll->id}}_large.jpg" alt="">
                            </a>
                        @endif
                        @if($poll->youtube_url)
                            <p>{!! showEmbedYoutube($poll->youtube_url) !!}</p>
                        @endif
                        @if($poll->twitter_url)
                            <p>{!! showEmbedTwitter($poll->twitter_url) !!}</p>
                        @endif
                        @if($poll->instagram_url)
                            <p>{!! showEmbedInstagram($poll->instagram_url) !!}</p>
                        @endif
                        <form action="{{ url('/vote') }}" method="POST" class="mt-4">
                            @csrf
                            @for($i = 0; $i < count($poll->poll_options); $i++)
                                <p>
                                    <input type="radio" name="poll_option_id" id="option{{ $poll->poll_options[$i]->id }}" value="{{ $poll->poll_options[$i]->id }}" >
                                    <label for="option{{ $poll->poll_options[$i]->id }}">{{ $poll->poll_options[$i]->content }} ({{ $poll->poll_options[$i]->votes->count() }})</label>
                                </p>
                            @endfor
                            <p>
                                <input type="submit" value="Submit" class="btn btn-primary">
                            </p>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

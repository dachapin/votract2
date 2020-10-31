@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($polls as $poll)
                <div class="card mb-3 poll" data-poll-number="{{ $poll->id }}">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-left">
                                @if(isset($poll->user->id) && file_exists('img/users/'. $poll->user->id . '_large.jpg' ))
                                    <a href="{{ url('/user/'. $poll->user->id ) }}" class="profile-image">
                                        <img class="img-fluid" src="{{ url('/') }}/img/users/{{$poll->user->id}}_large.jpg" alt="">
                                    </a>
                                @else
                                    <div class="mb-2">
                                        <p class="profile-image">
                                            <img class="circle" src="{{ url('') }}/img/users/default.png" alt="">
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <div class="media-body">
                                @if(isset($poll->user->name))
                                   <p><a href="{{ url('/user/'. $poll->user->id ) }}">{{ $poll->user->name }}</a></p>
                                @else
                                    <p>Anonymous</p>
                                @endif
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
                                    <input type="radio" name="poll_option_id" id="option{{ $poll->poll_options[$i]->id }}" value="{{ $poll->poll_options[$i]->id }}" class="poll-options" >
                                    <label for="option{{ $poll->poll_options[$i]->id }}">{{ $poll->poll_options[$i]->content }} ({{ $poll->poll_options[$i]->votes->count() }})</label>
                                </p>
                            @endfor
                            <p>
                                <input type="submit" value="Submit" class="btn btn-primary">
                            </p>
                        </form>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <time class="float-right">{{ $poll->created_at }}</time>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <a href="{{ url('/poll/create') }}" class="btn btn-primary">Create new Poll</a>
        </div>
    </div>
</div>
@endsection

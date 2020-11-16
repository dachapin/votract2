@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
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
                        @isset($poll->user->id)
                            @if(Auth::user() && Auth::id() === $poll->user->id )
                                <div class="col-6 mb-2">
                                    <form class="float-right" action="{{ url('/poll') }}/{{ $poll->id }}" method="post">
                                        @csrf
                                        @method("DELETE")
                                        <input class="btn btn-sm btn-outline-danger mb-2" type="submit" value="Delete">
                                    </form>
                                </div>
                            @endif
                        @endisset
                    </div>
                    <h2 class="h5">{{ $poll->title }}</h2>
                    @if(file_exists('img/polls/'. $poll->id . '_large.jpg' ))
                        <p><img class="img-fluid" src="{{ url('/') }}/img/polls/{{$poll->id}}_large.jpg" alt=""></p>
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
                            @if(Auth::user())
                                <p>
                                    <input type="radio" name="poll_option_id" id="option{{ $poll->poll_options[$i]->id }}" value="{{ $poll->poll_options[$i]->id }}" class="poll-options" {{ isVotedThisOptionId($vote_poll_options,$poll->poll_options[$i]->id) ? 'checked' : '' }}>
                                    <label for="option{{ $poll->poll_options[$i]->id }}">{{ $poll->poll_options[$i]->content }} ({{ $poll->poll_options[$i]->votes->count() }})</label>
                                </p>
                            @else
                                @isset(session('voted')['poll_id_'.$poll->id])
                                    <p>
                                        <input type="radio" name="poll_option_id" id="option{{ $poll->poll_options[$i]->id }}" value="{{ $poll->poll_options[$i]->id }}" class="poll-options" {{ isVotedThisOptionId(session('voted'),$poll->poll_options[$i]->id) ? 'checked' : '' }}>
                                        <label for="option{{ $poll->poll_options[$i]->id }}">{{ $poll->poll_options[$i]->content }} ({{ $poll->poll_options[$i]->votes->count() }})</label>
                                    </p>
                                @else
                                    <p>
                                        <input type="radio" name="poll_option_id" id="option{{ $poll->poll_options[$i]->id }}" value="{{ $poll->poll_options[$i]->id }}" class="poll-options">
                                        <label for="option{{ $poll->poll_options[$i]->id }}">{{ $poll->poll_options[$i]->content }} ({{ $poll->poll_options[$i]->votes->count() }})</label>
                                    </p>
                                @endisset
                            @endif
                        @endfor
                        @if(Auth::user())
                            @if(isVoted($vote_polls,$poll->id) === false)
                                <p>
                                    <input type="submit" value="Submit" class="btn btn-primary">
                                </p>
                            @endif
                        @else
                            @if(isset(session('voted')['poll_id_'.$poll->id]) === false)
                                <p>
                                    <input type="submit" value="Submit" class="btn btn-primary">
                                </p>
                            @endif
                        @endif
                    </form>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <time class="float-right">{{ $poll->created_at }}</time>
                        </div>
                    </div>
                    @if(Auth::user())
                        <hr>
                        <form action="{{ url('/comment') }}" method="POST" class="mt-4 mb-4">
                            @csrf
                            <input type="hidden" name="poll_id" value="{{ $poll->id }}">
                            <input type="text" name="content" class="form-control" placeholder="Comments....">
                        </form>
                    @else
                        <hr>
                        <p class="mb-4"><a href="{{ url('/login') }}">Login</a> to comment</p>
                    @endif
                    @if(count($poll->comments) > 0)
                        @foreach($poll->comments as $commnet)
                            <div class="row mb-2">
                                <div class="col-2">
                                    <a href="{{ url('/user/'. $commnet->user->id ) }}"><img src="https://tekumon.com/wp-content/themes/myTheme/src/images/tekumom-profile.jpg" alt="" class="rounded-circle width-100"></a>
                                </div>
                                <div class="col-10">
                                    <p class="mb-1 h7 text-secondary">By <a href="{{ url('/user/'. $commnet->user->id ) }}">{{ $commnet->user->name }}</a></p>
                                    <p class="">{{ $commnet->content }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

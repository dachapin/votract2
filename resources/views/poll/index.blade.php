@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(isset($user))
                @if(0 < count($followings))
                    @foreach ($followings as $following )
                        @foreach ($following->polls as $poll )
                            <?php
                                $total_votes = $poll->votes->count();
                            ?>
                            <div class="card mb-3 poll" data-poll-number="{{ $poll->id }}">
                                <div class="card-body">
                                    <div class="d-flex flex-row align-items-center">
                                        <div class="poll-user-profile-image">
                                            @if(isset($poll->user->id) && file_exists('img/users/'. $poll->user->id . '_large.jpg' ))
                                                <a href="{{ url('/user/'. $poll->user->id ) }}">
                                                    <img class="" src="{{ url('/') }}/img/users/{{$poll->user->id}}_large.jpg" alt="" width="60px">
                                                </a>
                                            @else
                                                <div class="mb-2">
                                                    <img class="circle" src="{{ url('') }}/img/users/default.png" alt="" width="60px">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="poll-user-name">
                                            @if(isset($poll->user->name))
                                                <span><a href="{{ url('/user/'. $poll->user->id ) }}">{{ $poll->user->name }}</a></span>
                                            @else
                                                <span>Anonymous</span>
                                            @endif
                                        </div>
                                    </div>
                                    <h2 class="poll-title"><a href="{{ url('/poll/'. $poll->id ) }}">{{ $poll->title }}</a></h2>
                                    @if(file_exists('img/polls/'. $poll->id . '_large.jpg' ))
                                        <a href="{{ url('/poll/'. $poll->id ) }}" class="poll-image">
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
                                    <form action="{{ url('/vote') }}" method="POST" class="poll-form-wrap">
                                        @csrf
                                        @for($i = 0; $i < count($poll->poll_options); $i++)
                                            <?php
                                                $total_voted_poll_option_id = $poll->poll_options[$i]->votes->count();
                                            ?>
                                            @if(Auth::user())
                                                @if(isVoted($voted_polls_by_user,$poll->id) === true )
                                                    @if(isThisOptionIdVotedByUser($voted_poll_option_ids_by_user,$poll->poll_options[$i]->id))
                                                        <div class="poll-result-wrap">
                                                            <span class="poll-result-option-content">{{ $poll->poll_options[$i]->content }}</span>
                                                            <div class="poll-graph-wrap">
                                                                @if(calculatePercentage($total_votes,$total_voted_poll_option_id) > 1)
                                                                    <div class="bar bar-green graph" style="{{ 'width:' . calculatePercentage($total_votes,$total_voted_poll_option_id) . '%;' }}">
                                                                        <div class="graph-content">
                                                                            <span class="graph-content-percentage">
                                                                                {{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="bar bar-green graph" style="width: 0.5%;"></div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="poll-result-wrap">
                                                            <span class="poll-result-option-content">{{ $poll->poll_options[$i]->content }}</span>
                                                            <div class="poll-graph-wrap">
                                                                @if(calculatePercentage($total_votes,$total_voted_poll_option_id) > 1)
                                                                    <div class="bar bar-gray graph" style="{{ 'width:' . calculatePercentage($total_votes,$total_voted_poll_option_id) . '%;' }}">
                                                                        <div class="graph-content">
                                                                            <span class="graph-content-percentage">
                                                                                {{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div for="option{{ $poll->poll_options[$i]->id }}" class="bar bar-gray graph" style="width: 0.5%;">
                                                                        <div class="graph-content">{{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}</div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="poll-wrap d-flex">
                                                        <div class="radio-btn">
                                                            <input type="radio" name="poll_option_id" id="option{{ $poll->poll_options[$i]->id }}" value="{{ $poll->poll_options[$i]->id }}" class="poll-options"}>
                                                        </div>
                                                        <div class="poll-option">
                                                            <label class="poll-option-content" for="option{{ $poll->poll_options[$i]->id }}">{{ $poll->poll_options[$i]->content }}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                @isset(session('voted')['poll_id_'.$poll->id])
                                                    @if(isThisOptionIdVotedByNonuser($poll->poll_options[$i],session('voted')['poll_id_'.$poll->id]))
                                                        <?php
                                                            $percentage = calculatePercentage($total_votes,$total_voted_poll_option_id);
                                                        ?>
                                                        <div class="poll-result-wrap">
                                                            <span class="poll-result-option-content">{{ $poll->poll_options[$i]->content }}</span>
                                                            <div class="poll-graph-wrap">
                                                                @if(calculatePercentage($total_votes,$total_voted_poll_option_id) > 1)
                                                                    <div class="bar bar-green graph" style="{{ 'width:' . calculatePercentage($total_votes,$total_voted_poll_option_id) . '%;' }}">
                                                                        <div class="graph-content">
                                                                            <span class="graph-content-percentage">
                                                                                {{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="bar bar-green graph" style="width: 0.5%;"></div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="poll-result-wrap">
                                                            <span class="poll-result-option-content">{{ $poll->poll_options[$i]->content }}</span>
                                                            <div class="poll-graph-wrap">
                                                                @if(calculatePercentage($total_votes,$total_voted_poll_option_id) > 1)
                                                                    <div class="bar bar-gray graph" style="{{ 'width:' . calculatePercentage($total_votes,$total_voted_poll_option_id) . '%;' }}">
                                                                        <div class="graph-content">
                                                                            <span class="graph-content-percentage">
                                                                                {{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div for="option{{ $poll->poll_options[$i]->id }}" class="bar bar-gray graph" style="width: 0.5%;">
                                                                        <div class="graph-content">{{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}</div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="poll-wrap d-flex">
                                                        <div class="radio-btn">
                                                            <input type="radio" name="poll_option_id" id="option{{ $poll->poll_options[$i]->id }}" value="{{ $poll->poll_options[$i]->id }}" class="poll-options" {{ isThisOptionIdVotedByUser($voted_polls_by_user,$poll->poll_options[$i]->id) ? 'checked' : '' }}>
                                                        </div>
                                                        <div class="poll-option">
                                                            <label class="poll-option-content" for="option{{ $poll->poll_options[$i]->id }}">{{ $poll->poll_options[$i]->content }}</label>
                                                        </div>
                                                    </div>
                                                @endisset
                                            @endif
                                        @endfor
                                        <!-- Submit Area -->
                                        @if(Auth::user())
                                            @if(isVoted($voted_polls_by_user,$poll->id) === false )
                                                <p>
                                                    <input type="submit" value="Vote" class="btn btn-primary mt-1">
                                                </p>
                                                <div class="row">
                                                    <div class="col-6">

                                                    </div>
                                                    <div class="col-6">
                                                        <time class="poll-time float-right">{{ $poll->created_at }}</time>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p class="total-votes">{{ $total_votes }} votes</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <time class="poll-time float-right">{{ $poll->created_at }}</time>
                                                    </div>
                                                </div>
                                                <a href="{{ url('/poll/'. $poll->id ) }}" class="poll-top-page-comment mt-3">Comment......</a>
                                            @endif
                                        @else
                                            @if(isset(session('voted')['poll_id_'.$poll->id]) === false)
                                                <p>
                                                    <input type="submit" value="Vote" class="btn btn-primary mt-1">
                                                </p>
                                                <div class="row">
                                                    <div class="col-6">

                                                    </div>
                                                    <div class="col-6">
                                                        <time class="poll-time float-right">{{ $poll->created_at }}</time>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p class="total-votes">{{ $total_votes }} votes</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <time class="poll-time float-right">{{ $poll->created_at }}</time>
                                                    </div>
                                                </div>
                                                <a href="{{ url('/poll/'. $poll->id ) }}" class="poll-top-page-comment mt-3">Comment......</a>
                                            @endif
                                        @endif
                                        <!-- End of Submit Area -->
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @else
                    <h2>No Posts</h2>
                @endif
            @else
                @if(0 < count($polls))
                    @foreach($polls as $poll)
                        <?php
                            $total_votes = $poll->votes->count();
                        ?>
                        <div class="card mb-3 poll" data-poll-number="{{ $poll->id }}">
                            <div class="card-body">
                                <div class="d-flex flex-row align-items-center">
                                    <div class="poll-user-profile-image">
                                        @if(isset($poll->user->id) && file_exists('img/users/'. $poll->user->id . '_large.jpg' ))
                                            <a href="{{ url('/user/'. $poll->user->id ) }}">
                                                <img class="" src="{{ url('/') }}/img/users/{{$poll->user->id}}_large.jpg" alt="" width="60px">
                                            </a>
                                        @else
                                            <div class="mb-2">
                                                <img class="circle" src="{{ url('') }}/img/users/default.png" alt="" width="60px">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="poll-user-name">
                                        @if(isset($poll->user->name))
                                            <span><a href="{{ url('/user/'. $poll->user->id ) }}">{{ $poll->user->name }}</a></span>
                                        @else
                                            <span>Anonymous</span>
                                        @endif
                                    </div>
                                </div>
                                <h2 class="poll-title"><a href="{{ url('/poll/'. $poll->id ) }}">{{ $poll->title }}</a></h2>
                                @if(file_exists('img/polls/'. $poll->id . '_large.jpg' ))
                                    <a href="{{ url('/poll/'. $poll->id ) }}" class="poll-image">
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
                                <form action="{{ url('/vote') }}" method="POST" class="poll-form-wrap">
                                    @csrf
                                    @for($i = 0; $i < count($poll->poll_options); $i++)
                                        <?php
                                            $total_voted_poll_option_id = $poll->poll_options[$i]->votes->count();
                                        ?>
                                        @if(Auth::user())
                                            @if(isVoted($voted_polls_by_user,$poll->id) === true )
                                                @if(isThisOptionIdVotedByUser($voted_poll_option_ids_by_user,$poll->poll_options[$i]->id))
                                                    <div class="poll-result-wrap">
                                                        <span class="poll-result-option-content">{{ $poll->poll_options[$i]->content }}</span>
                                                        <div class="poll-graph-wrap">
                                                            @if(calculatePercentage($total_votes,$total_voted_poll_option_id) > 1)
                                                                <div class="bar bar-green graph" style="{{ 'width:' . calculatePercentage($total_votes,$total_voted_poll_option_id) . '%;' }}">
                                                                    <div class="graph-content">
                                                                        <span class="graph-content-percentage">
                                                                            {{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="bar bar-green graph" style="width: 0.5%;"></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="poll-result-wrap">
                                                        <span class="poll-result-option-content">{{ $poll->poll_options[$i]->content }}</span>
                                                        <div class="poll-graph-wrap">
                                                            @if(calculatePercentage($total_votes,$total_voted_poll_option_id) > 1)
                                                                <div class="bar bar-gray graph" style="{{ 'width:' . calculatePercentage($total_votes,$total_voted_poll_option_id) . '%;' }}">
                                                                    <div class="graph-content">
                                                                        <span class="graph-content-percentage">
                                                                            {{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div for="option{{ $poll->poll_options[$i]->id }}" class="bar bar-gray graph" style="width: 0.5%;">
                                                                    <div class="graph-content">{{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="poll-wrap d-flex">
                                                    <div class="radio-btn">
                                                        <input type="radio" name="poll_option_id" id="option{{ $poll->poll_options[$i]->id }}" value="{{ $poll->poll_options[$i]->id }}" class="poll-options"}>
                                                    </div>
                                                    <div class="poll-option">
                                                        <label class="poll-option-content" for="option{{ $poll->poll_options[$i]->id }}">{{ $poll->poll_options[$i]->content }}</label>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            @isset(session('voted')['poll_id_'.$poll->id])
                                                @if(isThisOptionIdVotedByNonuser($poll->poll_options[$i],session('voted')['poll_id_'.$poll->id]))
                                                    <?php
                                                        $percentage = calculatePercentage($total_votes,$total_voted_poll_option_id);
                                                    ?>
                                                    <div class="poll-result-wrap">
                                                        <span class="poll-result-option-content">{{ $poll->poll_options[$i]->content }}</span>
                                                        <div class="poll-graph-wrap">
                                                            @if(calculatePercentage($total_votes,$total_voted_poll_option_id) > 1)
                                                                <div class="bar bar-green graph" style="{{ 'width:' . calculatePercentage($total_votes,$total_voted_poll_option_id) . '%;' }}">
                                                                    <div class="graph-content">
                                                                        <span class="graph-content-percentage">
                                                                            {{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="bar bar-green graph" style="width: 0.5%;"></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="poll-result-wrap">
                                                        <span class="poll-result-option-content">{{ $poll->poll_options[$i]->content }}</span>
                                                        <div class="poll-graph-wrap">
                                                            @if(calculatePercentage($total_votes,$total_voted_poll_option_id) > 1)
                                                                <div class="bar bar-gray graph" style="{{ 'width:' . calculatePercentage($total_votes,$total_voted_poll_option_id) . '%;' }}">
                                                                    <div class="graph-content">
                                                                        <span class="graph-content-percentage">
                                                                            {{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div for="option{{ $poll->poll_options[$i]->id }}" class="bar bar-gray graph" style="width: 0.5%;">
                                                                    <div class="graph-content">{{ calculatePercentage($total_votes,$total_voted_poll_option_id ) }}</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="poll-wrap d-flex">
                                                    <div class="radio-btn">
                                                        <input type="radio" name="poll_option_id" id="option{{ $poll->poll_options[$i]->id }}" value="{{ $poll->poll_options[$i]->id }}" class="poll-options" {{ isThisOptionIdVotedByUser($voted_polls_by_user,$poll->poll_options[$i]->id) ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="poll-option">
                                                        <label class="poll-option-content" for="option{{ $poll->poll_options[$i]->id }}">{{ $poll->poll_options[$i]->content }}</label>
                                                    </div>
                                                </div>
                                            @endisset
                                        @endif
                                    @endfor
                                    <!-- Submit Area -->
                                    @if(Auth::user())
                                        @if(isVoted($voted_polls_by_user,$poll->id) === false )
                                            <p>
                                                <input type="submit" value="Vote" class="btn btn-primary mt-1">
                                            </p>
                                            <div class="row">
                                                <div class="col-6">

                                                </div>
                                                <div class="col-6">
                                                    <time class="poll-time float-right">{{ $poll->created_at }}</time>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="total-votes">{{ $total_votes }} votes</p>
                                                </div>
                                                <div class="col-6">
                                                    <time class="poll-time float-right">{{ $poll->created_at }}</time>
                                                </div>
                                            </div>
                                            <a href="{{ url('/poll/'. $poll->id ) }}" class="poll-top-page-comment mt-3">Comment......</a>
                                        @endif
                                    @else
                                        @if(isset(session('voted')['poll_id_'.$poll->id]) === false)
                                            <p>
                                                <input type="submit" value="Vote" class="btn btn-primary mt-1">
                                            </p>
                                            <div class="row">
                                                <div class="col-6">

                                                </div>
                                                <div class="col-6">
                                                    <time class="poll-time float-right">{{ $poll->created_at }}</time>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="total-votes">{{ $total_votes }} votes</p>
                                                </div>
                                                <div class="col-6">
                                                    <time class="poll-time float-right">{{ $poll->created_at }}</time>
                                                </div>
                                            </div>
                                            <a href="{{ url('/poll/'. $poll->id ) }}" class="poll-top-page-comment mt-3">Comment......</a>
                                        @endif
                                    @endif
                                    <!-- End of Submit Area -->
                                </form>
                            </div>
                        </div>
                    @endforeach
                    {{ $polls->links() }}
                @else
                    <h2>No polls</h2>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card">
                    <div class="card-header">{{ __('Articles') }}</div>

                    <div class="card-body">
                        @foreach($polls as $poll)
                            <p><a href="{{ url('/poll/'. $poll->id ) }}">{{ $poll->title }}</a></p>
                        @endforeach
                    </div>
                </div>
                <div class="card-body">
                    @if(auth()->user())
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    @endif
                    @if(!auth()->user())
                        <h2>Articles</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

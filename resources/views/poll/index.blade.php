@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($polls as $poll)
                <div class="card mb-3">
                    <div class="card-body">
                            <p class="mb-0"><a href="{{ url('/poll/'. $poll->id ) }}">{{ $poll->title }}</a></p>
                    </div>
                </div>
            @endforeach
            <a href="{{ url('/poll/create') }}" class="btn btn-primary">Create new Poll</a>
        </div>
    </div>
</div>
@endsection

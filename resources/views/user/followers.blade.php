@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h1>Followers</h1>
                    @foreach($followers as $follower)
                        <a href="{{ url('') }}/user/{{ $follower->id }}">{{ $follower->name }}</a>
                        <hr>
                        <br>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

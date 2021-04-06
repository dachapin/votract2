@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h1>Followings</h1>
                    @foreach($followings as $following)
                        <a href="{{ url('') }}/user/{{ $following->id }}">{{ $following->name }}</a>
                        <hr>
                        <br>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

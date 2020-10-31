@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('/user') }}/{{ $user->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @if(Auth::user() && file_exists('img/users/'. $user->id . '_large.jpg' ))
                            <div class="mb-2">
                                <p>
                                    <a href="{{ url('') }}/img/users/{{$user->id}}_large.jpg" class="profile-image">
                                        <img class="" style="max-width: 400px; max-height:300px;" src="{{ url('') }}/img/users/{{$user->id}}_large.jpg" alt="">
                                    </a>
                                </p>
                                <a href="{{ url('') }}/delete-images/user/{{ $user->id }}" class="btn btn-outline-danger">Delete</a>
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
                        <div class="form-group">
                            <label for="image">Image</label>
                            <p>
                                <input type="file" class=" {{ $errors->has('image') ? 'border-danger' : '' }}" id="image" name="image" value="">
                            </p>
                            <small class="form-text text-danger">{!! $errors->first('image') !!}</small>
                        </div>
                        <p>Name</p>
                        <p>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                        </p>
                        <p>Username</p>
                        <p>
                            <input type="text" name="slug" class="form-control" value="{{ $user->slug }}">
                        </p>
                        <p>Description</p>
                        <p><textarea name="description" class="form-control">{{ $user->description }}</textarea></p>
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

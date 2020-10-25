@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create') }}</div>

                <div class="card-body">
                    <form action="{{ url('/poll') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <p>Your Question</p>
                        <p>
                            <textarea name="title" id="" cols="0" rows="5" class="form-control">{{ old('title') }}</textarea>
                        </p>
                        <small class="form-text text-danger">{!! $errors->first('title') !!}</small>
                        <p>Image</p>
                        <p>
                            <input type="file" class="" name="image" enctype="multipart/form-data">
                        </p>
                        @for($i = 0; $i < 10; $i++)
                            <p>Option{{ $i+1 }}</p>
                            <p><input type="text" name="poll_option[]" value="{{ old('poll_option.'.$i) }}" class="form-control"></p>
                            <small class="form-text text-danger">{!! $errors->first('poll_option.'.$i) !!}</small>
                        @endfor
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

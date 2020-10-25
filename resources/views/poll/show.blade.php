@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <time class="float-right">{{ $poll->created_at }}</time>
                        </div>
                    </div>
                    <h2 class="h5">{{ $poll->title }}</h2>
                    <p>
                        <?php
                            $youtube_url = createYoutuveFullUrl('https://youtu.be/pm2NKN_sjlA');
                        ?>
                        <div class="youtube">
                            <iframe width="560" height="315" src="{{ $youtube_url }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </p>
                    <form action="{{ url('/vote') }}" method="POST" class="mt-4">
                        @csrf
                        @for($i = 0; $i < count($poll_options); $i++)
                        <p>
                            <input type="radio" name="poll_option_id" id="option{{ $i }}" value="{{ $poll_options[$i]->id }}" >
                            <label for="option{{ $i }}">{{ $poll_options[$i]->content }} ({{ $poll_options[$i]->votes->count() }})</label>
                        </p>
                        @endfor
                        <p>
                            <input type="submit" value="Submit" class="btn btn-primary">
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

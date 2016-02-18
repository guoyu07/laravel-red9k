@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ $post->url }}">{{ $post->title }}</a>
                </div>

                <div class="panel-body">
                    <a href="{{route('comment', ['postId' => $post->id])}}">Post a comment</a>
                    @foreach ($comments as $comment)
                        <div>{{ $comment->text }}</div>
                    @endforeach
                </div>
        </div>
    </div>
@endsection

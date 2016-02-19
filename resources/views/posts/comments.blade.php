@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ $post->url }}">{{ $post->title }}</a>
                </div>
                {{ csrf_field() }}
                <div class="panel-body">

                    <!-- Comment button -->
                    @if (Auth::check())
                    <button id="/comment/{{$post->id}}" class="btn btn-primary btn-sm"><i class="fa fa-comments-o"></i> Post a comment</button><br><br>
                    @else
                    Log in to post comments<br><br>
                    @endif

                    <div id="comments">
                        @foreach ($comments as $comment)
                            <div class="well">
                                <div>{{ $comment->text }} - {{ $comment->user->name }}</div>

                                <!-- Reply Button -->
                                <div>
                                    @if (Auth::check())
                                    <button id="/comment/{{$post->id}}/{{ $comment->id }}" class="btn btn-primary btn-xs">
                                        <i class="fa fa-reply"></i> Reply
                                    </button>
                                    @endif
                                </div>
                                @if ($comment->replies)
                                    <br>
                                    @foreach($comment->replies as $reply)
                                        <div class="well">
                                            <div>{{ $reply->text }} - {{ $reply->user->name }}</div>

                                            <!-- Reply Button -->
                                            <div>
                                                @if (Auth::check())
                                                <button id="/comment/{{$post->id}}/{{ $reply->id }}" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-reply"></i> Reply
                                                </button>
                                                @endif
                                            </div>
                                        @if ($reply->replies)
                                            <br>
                                            @foreach($reply->replies as $reply2)
                                                <div class="well">
                                                    <div>{{ $reply2->text }} - {{ $reply2->user->name }}</div>

                                                    <!-- Go to Comment Thread -->
                                                    <div>
                                                        <a href="/comment/{{$post->id}}/{{ $reply2->id }}/full" class="btn btn-success btn-xs">
                                                            <i class="fa fa-reply"></i> Go to full comment thread
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
        </div>
    </div>
<script>
    var csrf = document.getElementsByName('_token')[0].value;
    $("button[id^='/comment']").click(function()
    {
        if (!$(this).attr('clicked'))
        {
            $(this).text('Submit');
            $(this).parent().prepend('<div><textarea id="' + $(this).attr('id') + '"></textarea></div>');
            $(this).attr('clicked', 'true');
        }
        else
        {
            var id = $(this).attr('id');
            var text = $("textarea[id='" + id + "']").val();
            $.post(id, { _token: csrf, text: text, comment_id: 0 }, function(data)
            {
                $("#comments").prepend(
                        'Comment Posted!' +
                        '<div class="well">' +
                            data.comment +
                            ' - {{Auth::check() ? Auth::user()->name : ''}}' +
                        '</div>'
                );
            });
        }
    });
</script>
@endsection

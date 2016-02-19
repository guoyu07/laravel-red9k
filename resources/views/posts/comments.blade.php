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
                    <button id="/comment/{{$post->id}}" class="btn btn-primary btn-sm"><i class="fa fa-comments-o"></i> Post a comment</button><br><br>
                    <div id="comments">
                        @foreach ($comments as $comment)
                            <div class="well">
                                <div>{{ $comment->text }} - {{ $comment->user->name }}</div>

                                <!-- Reply Button -->
                                <div>
                                    <button id="/comment/{{$post->id}}/{{ $comment->id }}" class="btn btn-primary btn-xs">
                                        <i class="fa fa-reply"></i> Reply
                                    </button>
                                </div>
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
                        '<div class="well">' +
                            data.comment +
                            ' - {{Auth::user()->name}}' +
                            '<div>' +
                                '<button id="/comment/{{$post->id}}/' + data.id + '" class="btn btn-primary btn-xs">' +
                                    '<i class="fa fa-reply"></i> Reply' +
                                '</button>' +
                            '</div>' +
                        '</div>'
                );
            });
        }
    });
</script>
@endsection

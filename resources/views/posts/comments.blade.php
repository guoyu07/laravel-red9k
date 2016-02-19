@extends('layouts.app')

<?php
/**
 * Recursive function to output the html we need
 * @param $comments
 * @param $post
 */
function displayComments($comments, $post)
{
    foreach($comments as $comment)
    {
        echo '<div class="well">';
        echo '<div>' . $comment->text . '-' . $comment->user->name . '</div>';
        echo '<div>';
        if (Auth::check())
        {
            echo '<button id="/comment/' . $post->id . '/' . $comment->id .'" class="btn btn-primary btn-xs"><i class="fa fa-reply"></i> Reply</button>';
        }
        if ($comment->replies)
        {
            displayComments($comment->replies, $post);
        }
        echo '</div>';
        echo '</div>';
    }
}
?>

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
                        <?php displayComments($comments, $post) ?>
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

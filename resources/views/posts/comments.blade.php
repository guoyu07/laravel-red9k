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
        echo '<div>';
        echo $comment->text . '-' . $comment->user->name . '</div>';
        echo '<div>';
        if (Auth::check())
        {
            if (Auth::user()->admin)
            {
                echo "<form action='/comment/" . $comment->id . "/delete' method='POST'>";
                echo csrf_field();
                echo method_field('DELETE');
                echo "<button type='submit' id='delete-comment-" . $comment->id . "' class='btn btn-danger btn-xs'>";
                echo "<i class='fa fa-btn fa-trash'></i> Delete Comment";
                echo "</button>";
                echo "</form>";
                echo "<br>";
            }
            echo '<button id="/comment/' . $post->id . '/' . $comment->id .'" class="btn btn-primary btn-xs"><i class="fa fa-reply"></i> Reply</button> ';
            echo '<button id="/commend/' . $comment->id .'" class="btn btn-success btn-xs"><i class="fa fa-thumbs-up"></i> Commend (' . $comment->voteCount .')</button> ';
            if (Auth::user()->admin)
            {
                echo "<a href=" . route('confirmBan', ['userId' => $comment->user->id]) . " class='btn btn-danger btn-xs'><i class='fa fa-ban'></i> Ban User</a> ";
            }
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
                    <h3><a href="{{ $post->url }}">{{ $post->title }}</a></h3>
                </div>
                {{ csrf_field() }}
                <div class="panel-body">

                    <!-- Comment button -->
                    @if (Auth::check())
                    <button id="/comment/{{$post->id}}" class="btn btn-primary btn-sm"><i class="fa fa-comments-o"></i> Post a comment</button><br><br>
                    @else
                    <a href="/login">Sign in</a> to participate<br><br>
                    @endif

                    <div id="comments">
                        <?php displayComments($comments, $post) ?>
                    </div>
                    {!! $comments->render() !!}
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

    var csrf = document.getElementsByName('_token')[0].value;
    $("button[id^='/commend']").click(function()
    {
        var button = $(this);
        $.post($(this).attr('id'), { _token: csrf }, function(data)
        {
            if (data.error)
            {
                button.append(data.error);
            }
            else
            {
                button.html('<i class="fa fa-thumbs-up"></i> Commend (' + data.votes + ')');
            }
        });
    });
</script>
@endsection

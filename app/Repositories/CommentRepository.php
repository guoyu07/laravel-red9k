<?php

namespace App\Repositories;

use App\Post;
use App\Comment;

class CommentRepository
{
    /**
     * Get the parent comments for a post with a certain parent id(0 for none)
     * Paginated by parent comments so the length is a bit unpredictable
     *
     * @param Post $post
     * @param integer $commentId
     * @return Comment Collection
     */
    public function forPost(Post $post, $commentId = 0)
    {
        return Comment::where('post_id', $post->id)
                ->where('comment_id', $commentId)
                ->simplePaginate(25);
    }

    /**
     * Get the users comments
     *
     * @param $user
     * @return Comment Collection
     */
    public function forUser($user)
    {
        return Comment::where('user_id', $user)
            ->simplePaginate(25);
    }
}

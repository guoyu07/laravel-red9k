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
     * @return Post Collection
     */
    public function forPost(Post $post, $commentId = 0)
    {
        return Comment::where('post_id', $post->id)
                ->where('comment_id', $commentId)
                ->simplePaginate(25);
    }
}

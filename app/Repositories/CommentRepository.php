<?php

namespace App\Repositories;

use App\Post;
use App\Comment;

class CommentRepository
{
    /**
     * Get the comments for this post
     *
     * @param  Post post
     * @return Collection
     */
    public function forPost(Post $post)
    {
        return Comment::where('post_id', $post->id)
                    ->get();
    }
}

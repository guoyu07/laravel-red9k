<?php

namespace App\Repositories;

use DB;
use App\Post;
use App\Vote;

class VoteRepository
{
    /**
     * Get the votes for this post
     *
     * @param  User  $user
     * @return Integer
     */
    public function forPost(Post $post)
    {
        return Vote::where('post_id', $post->id)
                    ->get();
    }
}

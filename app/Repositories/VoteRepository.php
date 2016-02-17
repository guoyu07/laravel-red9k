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
	
	/**
     * Count the votes for posts
     *
     * @param  User  $user
     * @return Integer
     */
    public function voteCount()
    {
        return DB::select(DB::raw('SELECT p.id, count(v.post_id) AS voteCount FROM posts p LEFT JOIN votes v on p.id = v.post_id GROUP BY p.id;'));
    }
	 
}

<?php

namespace App\Repositories;

use App\User;
use App\Post;

class PostRepository
{
    /**
     * Get all of the posts for a given user.
     *
     * @param  User  $user
     * @return Collection
     */
    public function forUser(User $user)
    {
        return Post::where('user_id', $user->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
    }
	
	/**
	 * Get all posts
	 *
	 * @return Collection
	 */
	public function all()
    {
        return Post::orderBy('votes', 'desc')
                    ->get();
    }
}

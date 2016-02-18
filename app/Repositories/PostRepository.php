<?php

namespace App\Repositories;

use App\User;
use App\Post;

class PostRepository
{
    /**
     * Get all of the posts for a given user.
     *
     * @param  $user
     * @return Collection
     */
    public function forUser($user)
    {
        return Post::where('user_id', $user)
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
        return Post::orderBy('voteCount', 'desc')
					->get();
    }

    /**
     * Find a post
     * @param $postId
     * @return Post
     */
    public function find($postId)
    {
        return Post::find($postId);
    }

	/**
	 * Find a post by url
	 * @param $url
	 * @return Post
	 */
	public function findByUrl($url)
	{
		return Post::where('url', $url)
					->firstOrFail();
	}

    /**
     * Get posts by category
     *
     * @return Collection
     */
    public function byCategory($category)
    {
        return Post::where('category', $category)
                    ->orderBy('voteCount', 'desc')
                    ->get();
    }
}

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
     * @return Post Collection
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
	 * @return Post Collection
	 */
	public function all()
    {
        return Post::orderBy('voteCount', 'desc')
					->simplePaginate(25);
    }

    /**
     * Find a post
     * @param $postId
     * @return Post Collection
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
	 * Find posts with a term in their title
	 * @param $term
	 * @return Post Collection
	 */
	public function search($term)
	{
		return Post::where('title', 'like', '%' . $term . '%')
					->orderBy('voteCount', 'desc')
					->simplePaginate(25);
	}

    /**
     * Get posts by category
     *
     * @return Post Collection
     */
    public function byCategory($category)
    {
        return Post::where('category', $category)
                    ->orderBy('voteCount', 'desc')
					->simplePaginate(25);
    }
}

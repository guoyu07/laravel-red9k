<?php

namespace App\Repositories;

use App\CommentVote;
use App\Comment;

class CommentVoteRepository
{
	/**
	 * Get the votes for this comment
	 *
	 * @param  Comment $comment
	 * @return Integer
	 */
	public function forComment(Comment $comment)
	{
		return CommentVote::where('comment_id', $comment->id)
			->get();
	}
}

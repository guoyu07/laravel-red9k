<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['text', 'voteCount'];

	/**
	 * The post that owns this comment.
	 */
	public function post()
	{
		return $this->belongsTo(Post::class);
	}

	/**
	 * The user that owns this comment.
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/*
	 * The votes that belong to this comment
	 */
	public function votes()
	{
		return $this->hasMany(CommentVote::class);
	}

	/*
	 * The replies to this comment
	 */
	public function replies()
	{
		return $this->hasMany(Comment::class);
	}

	/*
	 * The parent of this comment
	 */
	public function comment()
	{
		return $this->belongsTo(Comment::class);
	}
}

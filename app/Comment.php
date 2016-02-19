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
	protected $fillable = ['text'];

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
	 * The replies to this comment
	 */
	public function replies()
	{
		return $this->hasMany(Comment::class);
	}

	/*
	 * The parent of this comment
	 */
	public function parent()
	{
		return $this->belongsTo(Comment::class);
	}
}

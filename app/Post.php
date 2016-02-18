<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'url', 'category'];

    /**
     * The user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
	
	/**
	 * The votes this post has
	 */
	public function votes()
	{
		return $this->hasMany(Vote::class);
	}

	/*
	 * The comments this post has
	 */
	public function comments()
	{
		return $this->hasMany(Comment::class);
	}
}

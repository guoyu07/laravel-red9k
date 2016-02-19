<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;

class CommentVote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['address'];

    /**
     * The comment that owns this vote
     */
    public function post()
    {
        return $this->belongsTo(Comment::class);
    }
	
	/**
     * The user that owns this comment vote
     */
    public function user()
    {
        return $this->belongsTo(Comment::class);
    }
}

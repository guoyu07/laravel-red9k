<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['address'];

    /**
     * The post that owns this vote.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
	
	/**
     * The user that owns this vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

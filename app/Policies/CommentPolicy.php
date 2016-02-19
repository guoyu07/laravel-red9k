<?php

namespace App\Policies;

use App\User;
use App\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can delete the given post.
     *
     * @param  User  $user
     * @param  Comment $comment
     * @return bool
     */
    public function destroy(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id;
    }
}

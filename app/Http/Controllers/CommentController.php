<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use App\Comment;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
	/**
	 * The user repository instance.
	 *
	 * @var UserRepository
	 */
	protected $users;

	/**
	 * Create a new controller instance.
	 *
	 * @param  PostRepository  $posts
	 * @return void
	 */
	public function __construct(UserRepository $users)
	{
		$this->users = $users;
	}

	/**
	 * Comment creation form
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function create(Request $request, Post $post)
	{
		return view('comments.create', [
			'post' => $post
		]);
	}

	/**
	 * Create a comment
	 *
	 * @param  Request  $request
	 * @return Response (JSON)
	 */
	public function store(Request $request, Post $post, Comment $comment = null)
	{
		//check if they're banned
		$this->isBanned($request);

		$this->validate($request, [
			'text' => 'required|max:255',
		]);

		$newComment = new Comment;
		$newComment->text = $request->get('text');
		$newComment->post_id = $post->id;
		$newComment->comment_id = $comment->id;
		if (!$newComment->comment_id) $newComment->comment_id = 0;
		$newComment->user_id = $request->user()->id;
		$newComment->save();

		return response()->json(['comment' => $newComment->text, 'id' => $newComment->id]);
	}

	/**
	 * Destroy the given post.
	 *
	 * @param  Request  $request
	 * @param  Comment $comment
	 * @return Response
	 */
	public function destroy(Request $request, Comment $comment)
	{
		$this->authorize('destroy', $comment);

		$comment->delete();

		return redirect('/posts');
	}

	/**
	 * Check if user is banned.  If so force logout.
	 */
	private function isBanned(Request $request)
	{
		foreach($this->users->banned() as $user)
		{
			if ($user->address == request()->ip())
			{
				Auth::logout();
				return view('auth.banned');
			}
		}
		if ($request->user()->banned)
		{
			Auth::logout();
			return view('auth.banned');
		}
	}
}

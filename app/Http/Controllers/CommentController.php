<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use App\Comment;
use App\CommentVote;
use App\Repositories\UserRepository;
use App\Repositories\CommentVoteRepository;
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
	 * The comment vote repository instance.
	 *
	 * @var CommentRepository
	 */
	protected $commentVotes;

	/**
	 * Create a new controller instance.
	 *
	 * @param  UserRepository  $users
	 */

	public function __construct(UserRepository $users, CommentVoteRepository $commentVotes)
	{
		$this->users = $users;
		$this->commentVotes = $commentVotes;
	}

	/**
	 * Comment creation form
	 *
	 * @param Request $request
	 * @param Post $post
	 * @return view
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
	 * @return JSON
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
	 * Commend a comment
	 *
	 * @param  Request  $request
	 * @return Response (JSON)
	 */
	public function up(Request $request, Comment $comment)
	{
		$address = request()->ip();
		foreach ( $this->commentVotes->forComment($comment) as $vote )
		{
			if ($vote->address === $address)
			{
				return response()->json([
					'votes' => $comment->voteCount,
					'error' => 'This IP Address has already commended this comment'
				]);
			}
		}
		$vote = new CommentVote;
		$vote->address = $address;
		$vote->comment_id = $comment->id;
		$vote->save();
		$comment->voteCount = $comment->voteCount + 1;
		$comment->save();
		return response()->json(['votes' => $comment->voteCount]);
	}

	/**
	 * Destroy the comment.
	 *
	 * @param  Request $request
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
	 *
	 * @param Request $request
	 * @return view || none
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use App\Comment;

class CommentController extends Controller
{
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
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'text' => 'required|max:255',
		]);

		$comment = new Comment;
		$comment->text = $request->get('text');
		$comment->post_id = $request->input('id');
		$comment->user_id = $request->user()->id;
		$comment->save();

		return redirect(route('comments', ['post' => $request->input('id')]));
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\PostRepository;
use App\Vote;
use App\Post;

class CommentController extends Controller
{
	/**
	 * The post repository instance.
	 *
	 * @var PostRepository
	 */
	protected $posts;

	/**
	 * Create a new controller instance.
	 *
	 * @param  PostRepository  $posts
	 * @return void
	 */
	public function __construct(PostRepository $posts)
	{
		$this->posts = $posts;
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
	 * @return Response
	 */
	public function store(Request $request)
	{
		$post = $this->posts->find($request->input('id'));

		$this->validate($request, [
			'text' => 'required|unique:comments|max:255|',
		]);

		$post->comments()->create([
			'text' => $request->text,
		]);

		return redirect(route('comments', ['post' => $post]));
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

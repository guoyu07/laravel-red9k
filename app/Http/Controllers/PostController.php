<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Vote;
use App\Post;
use App\Repositories\PostRepository;
use App\Repositories\VoteRepository;
use App\Repositories\CommentRepository;

class PostController extends Controller
{
    /**
     * The post repository instance.
     *
     * @var PostRepository
     */
    protected $posts;
	
	/**
     * The vote repository instance.
     *
     * @var VoteRepository
     */
	protected $votes;

	/**
	 * The user repository instance.
	 *
	 * @var UserRepository
	 */
	protected $users;

	/**
	 * The comment repository instance.
	 *
	 * @var UserRepository
	 */
	protected $comments;

    /**
     * Create a new controller instance.
     *
     * @param  PostRepository  $posts
     * @return void
     */
    public function __construct(PostRepository $posts,
								VoteRepository $votes,
								UserRepository $users,
								CommentRepository $comments)
    {
		$this->users = $users;
        $this->posts = $posts;
		$this->votes = $votes;
		$this->comments = $comments;
    }

    /**
     * Display a list of posts ordered by votes
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('posts.index', [
			'posts' => $this->posts->all()
		]);
    }

	/**
	 * Post creation form
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function create(Request $request)
	{
		return view('posts.create');
	}

	/**
	 * Display a list of posts in a category ordered by votes
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function category(Request $request, $category)
	{
		return view('posts.index', [
			'posts' => $this->posts->byCategory($category),
			'category' => $category,
		]);
	}

	/**
	 * Display a list of posts in a category ordered by votes
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function search(Request $request)
	{
		$term = $_GET['q'];

		return view('posts.index', [
			'posts' => $this->posts->search($term),
			'term' => $term,
		]);
	}

    /**
     * Create or edit a post
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
			'url' => 'required|unique:posts|max:255',
        ]);

		$error = $this->validateUrl($request->input('url'));
		if ($error)
		{
			return redirect('/post/create')->withErrors([$error]);
		}
		

        $request->user()->posts()->create([
            'title' => $request->title,
			'url' => $request->url,
			'category' => $request->category,
        ]);

		return redirect('/posts');
	}

	/**
	 * Return comments for this post
	 *
	 * @param Request $request
	 * @param Post $post
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function comments(Request $request, Post $post)
	{
		$parents = $this->comments->forPost($post);
		return view('posts.comments', [
			'post' => $post,
			'comments' => $parents
		]);
	}

    /**
     * Destroy the given post.
     *
     * @param  Request  $request
     * @param  Post  $post
     * @return Response
     */
    public function destroy(Request $request, Post $post)
    {
        $this->authorize('destroy', $post);

        $post->delete();

        return redirect('/posts');
    }
	
	/**
     * Thumbs up a post
     *
     * @param  Request  $request
     * @return Response (JSON)
     */
	public function up(Request $request, Post $post)
    {
		$address = request()->ip();
		foreach ( $this->votes->forPost($post) as $vote )
		{
			if ($vote->address === $address)
			{
				return response()->json([
					'votes' => $post->voteCount,
					'error' => 'This IP Address has already voted on this post'
				]);
			}
		}
		$vote = new Vote;
		$vote->address = $address;
		$vote->post_id = $post->id;
		$vote->save();
		$post->voteCount = $post->voteCount + 1;
		$post->save();
        return response()->json(['votes' => $post->voteCount]); 
    }
	
	/**
     * Thumbs down a post
     *
     * @param  Request  $request
     * @return Response (JSON)
     */
	public function down(Request $request, Post $post)
    {
		$address = request()->ip();
		foreach ( $this->votes->forPost($post) as $vote )
		{
			if ($vote->address === $address)
			{
				return response()->json([
					'votes' => $post->voteCount,
					'error' => 'This IP Address has already voted on this post'
				]);
			}
		}
		$vote = new Vote;
		$vote->address = $address;
		$vote->post_id = $post->id;
		$vote->save();
		$post->voteCount = $post->voteCount - 1;
		$post->save();
        return response()->json(['votes' => $post->voteCount]); 
    }
	
	/**
     * Extra url validation
     *
     * @param  string url
     * @return string error || null
     */
	 public function validateUrl($url)
	 {
		if ( preg_match('/http/', $url) == 0 ) return "Url must begin with http";
		$ch = curl_init($url);
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (($code == 301) || ($code == 302)) {
			return "Url may not be a redirect";
		}
		if ( preg_match('/#/', $url) == 1 ) return "Url cannot contain '#'";
		return null;
	 }
}

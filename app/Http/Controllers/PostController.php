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
use Illuminate\Support\Facades\Auth;

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
	 * Return posts with 'term' in their title
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
     * Create a post
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
		//check if they're banned
		$this->isBanned($request);

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
		$comments = $this->comments->forPost($post);

		return view('posts.comments', [
			'post' => $post,
			'comments' => $comments,
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
	 private function validateUrl($url)
	 {
		 //if ( preg_match('/http/', $url) == 0 ) return "Url must begin with http";
		 $ch = curl_init($url);
		 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		 curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
		 curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 curl_setopt($ch, CURLOPT_MAX_RECV_SPEED_LARGE, 100000);
		 //max 500kb per request
		 $data = curl_exec($ch);
		 $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		 curl_close($ch);
		 if ($code == 301 || $code == 302) {
			 return "Invalid status code";
		 }
		 if ( preg_match('/#/', $url) == 1 ) return "Url cannot contain '#'";
		 return null;
	 }

	/**
	 * Check if user is banned.  If so force logout.
	 *
	 * @Params Request $request
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

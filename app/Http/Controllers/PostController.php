<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Vote;
use App\Post;
use App\Repositories\PostRepository;
use App\Repositories\VoteRepository;

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
     * Create a new controller instance.
     *
     * @param  PostRepository  $posts
     * @return void
     */
    public function __construct(PostRepository $posts, VoteRepository $votes)
    {
        $this->middleware('auth');

        $this->posts = $posts;
		$this->votes = $votes;
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
     * Display a list of posts ordered by votes
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('posts.index', [
			'posts' => $this->posts->all(),
		]);
    }
	
	 /**
     * Display a list of the users post ordered by create date
     *
     * @param  Request  $request
     * @return Response
     */
    public function userIndex(Request $request)
    {
        return view('posts.userIndex', [
            'posts' => $this->posts->forUser($request->user()),
        ]);
    }


    /**
     * Create a new post.
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
			return redirect('/posts')->withErrors([$error]);
		}
		

        $request->user()->posts()->create([
            'title' => $request->title,
			'url' => $request->url,
        ]);

        return redirect('/posts');
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
		$address = $_SERVER['REMOTE_ADDR'];
		foreach ( $this->votes->forPost($post) as $vote )
		{
			if ($vote->address === $address)
			{
				return response()->json(['votes' => $post->voteCount]); 
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
		$address = $_SERVER['REMOTE_ADDR'];
		foreach ( $this->votes->forPost($post) as $vote )
		{
			if ($vote->address === $address)
			{
				return response()->json(['votes' => $post->voteCount]); 
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
	 public function validateUrl(string $url)
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

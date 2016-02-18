<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\PostRepository;

class UserController extends Controller
{
    /**
     * The post repository instance.
     *
     * @var PostRepository
     */
    protected $posts;

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
    public function __construct(PostRepository $posts, UserRepository $users)
    {
        $this->users = $users;
        $this->posts = $posts;
    }

    /**
     * Display a list of the users posts ordered by create date
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request, $user)
    {
        return view('user.index', [
            'user' => $this->users->find($user),
            'posts' => $this->posts->forUser($user)
        ]);
    }
}

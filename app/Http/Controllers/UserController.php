<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Auth;

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
     * The post repository instance.
     *
     * @var PostRepository
     */
    protected $comments;

    /**
     * Create a new controller instance.
     *
     * @param  PostRepository  $posts
     * @return void
     */
    public function __construct(PostRepository $posts, UserRepository $users, CommentRepository $comments)
    {
        $this->users = $users;
        $this->posts = $posts;
        $this->comments = $comments;
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
            'posts' => $this->posts->forUser($user),
            'comments' => $this->comments->forUser($user)
        ]);
    }

    /**
     * Confirm page for bans
     *
     * @param $request
     * @param $user
     * @return view
     */
    public function confirmBan(Request $request, $user)
    {
        return view('user.ban', [
            'user' => $this->users->find($user),
        ]);
    }

    /**
     * Ban user
     *
     * @param Request $request
     * @param $user
     * @return Redirect
     */
    public function ban(Request $request, $user)
    {
        $userToBan = $this->users->find($user);
        $userToBan->banned = 1;
        $userToBan->save();
        return redirect('/posts');
    }

    /**
     * Check if user is banned.  If so force logout.
     *
     * @param Request $request
     * @return Logout || Redirect
     */
    public function isBanned(Request $request)
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
        return redirect('/posts');
    }
}

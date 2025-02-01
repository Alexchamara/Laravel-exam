<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user')->latest();
    
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
    
        $posts = $query->paginate(10)->withQueryString();
        return view('welcome', compact('posts'));
    }

    public function login()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        return view('auth.register');
    }

    public function dashboard()
    {
        return view('pages.dashboard');
    }
}

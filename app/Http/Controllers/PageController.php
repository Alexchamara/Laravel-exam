<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('welcome');
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

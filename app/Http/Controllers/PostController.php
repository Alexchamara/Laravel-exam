<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    //index method
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
        return view('pages.posts', compact('posts'));
    }

    //redirect to form page
    public function create()
    {
        return view('pages.post-create');
    }

    //store method
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $post = new Post($validated);
        $post->user_id = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image_path = $path;
        }

        $post->save();

        return back()->with('success', 'Post created successfully!');
    }

    //show method
    public function show(Post $post)
    {
        return view('pages.post-show', compact('post'));
    }

    //edit method
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('pages.post-edit', compact('post'));
    }

    //update method
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $path = $request->file('image')->store('posts', 'public');
            $validated['image_path'] = $path;
        }

        $post->update($validated);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully.');
    }

    //destroy method
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return redirect()->route('posts')->with('success', 'Post deleted successfully.');
    }
}
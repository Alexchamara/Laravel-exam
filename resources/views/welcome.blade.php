@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Latest Posts</h1>
        <p class="mt-2 text-gray-600">Read our latest stories and insights</p>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form action="{{ route('welcome') }}" method="GET" class="flex gap-2">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Search posts..." 
                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <button type="submit" 
                    class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('welcome') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if($posts->isEmpty())
        <div class="text-center py-8 text-gray-500">
            No posts found.
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    @if($post->image_path)
                        <img src="{{ Storage::url($post->image_path) }}" 
                             alt="Post image"
                             class="w-full h-48 object-cover">
                    @endif
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
                        <p class="text-gray-600 mb-4">{{ Str::limit($post->content, 150) }}</p>
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span>By {{ $post->user->name }}</span>
                            <span>{{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                        <a href="{{ route('posts.show', $post) }}" 
                           class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">
                            Read more →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
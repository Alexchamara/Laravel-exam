@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-sm rounded-lg p-6">
        <!-- Post Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>
            <div class="flex items-center text-gray-500 text-sm">
                <span>Posted by {{ $post->user->name }}</span>
                <span class="mx-2"> &bull; </span>
                <span>{{ $post->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <!-- Post Image (if exists) -->
        @if($post->image_path)
            <div class="mb-6">
                <img src="{{ Storage::url($post->image_path) }}" 
                     alt="Post image" 
                     class="w-full h-auto rounded-lg shadow-md">
            </div>
        @endif

        <!-- Post Content -->
        <div class="prose max-w-none mb-6">
            {{ $post->content }}
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center border-t pt-4">
            <a href="{{ route('posts') }}" 
               class="text-gray-600 hover:text-gray-900">
                &larr; Back to Posts
            </a>

            @can('update', $post)
                <div class="space-x-2">
                    <a href="{{ route('posts.edit', $post) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Edit Post
                    </a>
                    <form action="{{ route('posts.destroy', $post) }}" 
                          method="POST" 
                          class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                                onclick="return confirm('Are you sure you want to delete this post?')">
                            Delete Post
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif
</div>
@endsection
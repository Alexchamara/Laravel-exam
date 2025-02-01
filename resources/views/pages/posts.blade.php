@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Posts</h1>
        <a href="{{ route('posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create Post</a>
    </div>

    <div class="mb-6">
        <form action="{{ route('posts') }}" method="GET" class="flex gap-2">
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
                <a href="{{ route('posts') }}" 
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
        @foreach ($posts as $post)
            <x-post_card :post="$post" />
        @endforeach

        {{ $posts->links() }}
    @endif
</div>
@endsection
@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Posts</h1>
        <a href="{{ route('posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create Post</a>
    </div>

    @foreach ($posts as $post)
        {{-- <div class="bg-white shadow-sm rounded-lg p-6 mb-4">
            <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
            <p class="text-gray-600 mb-4">{{ Str::limit($post->content, 200) }}</p>
            <div class="flex justify-between items-center text-sm text-gray-500">
                <span>By {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</span>
                <div class="space-x-2">
                    <a href="{{ route('posts.show', $post) }}" class="text-blue-500">Read more</a>
                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="text-green-500">Edit</a>
                        <form class="inline" method="POST" action="{{ route('posts.destroy', $post) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div> --}}
        <x-post_card :post="$post" />
    @endforeach

    {{ $posts->links() }}
</div>
@endsection
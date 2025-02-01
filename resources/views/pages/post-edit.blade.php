@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-semibold mb-6">Edit Post</h1>

    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" 
                    value="{{ old('title', $post->title) }}"
                    class="mt-1 block w-full rounded-md border-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Content</label>
                <textarea name="content" 
                    rows="5" 
                    class="mt-1 block w-full rounded-md border-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Image</label>
                @if($post->image_path)
                    <div class="mt-2 mb-4">
                        <img src="{{ Storage::url($post->image_path) }}" 
                             alt="Current post image" 
                             class="w-48 h-48 object-cover rounded-md">
                    </div>
                @endif
                <input type="file" 
                       name="image" 
                       class="mt-1 block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('posts.show', $post) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Update Post
                </button>
            </div>
        </div>
    </form>

    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => {
                show = false;
                window.location.href = '{{ route('posts.show', $post) }}';
             }, 3000)"
             class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif
</div>
@endsection
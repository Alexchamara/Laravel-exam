@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-semibold mb-6">Create Post</h1>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" name="image" class="mt-1 block w-full">
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create Post</button>
                </div>

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => {
                        show = false;
                        window.location.href = '{{ route('posts') }}';
                    }, 5000)"
                        class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection

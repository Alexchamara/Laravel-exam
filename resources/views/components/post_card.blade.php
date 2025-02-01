<div class="bg-white shadow-sm rounded-lg p-6 mb-4">
    <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
    <p class="text-gray-600 mb-4">{{ Str::limit($post->content, 200) }}</p>
    <div class="flex justify-between items-center text-sm text-gray-500">
        <span>By {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</span>
        <div class="space-x-2">
            <a href="{{ route('posts.show', $post) }}" class="text-blue-500 hover:text-blue-700">Read more</a>
            @can('update', $post)
                <a href="{{ route('posts.edit', $post) }}" class="text-green-500 hover:text-green-700">Edit</a>
                <form class="inline" method="POST" action="{{ route('posts.destroy', $post) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="text-red-500 hover:text-red-700" 
                            onclick="return confirm('Are you sure you want to delete this post?')">
                        Delete
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
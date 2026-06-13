@extends('layouts.frontend')

@section('content')
<header class="py-12 fade-in">
    <h1 class="text-4xl font-bold mb-4">Saved Stories</h1>
    <p class="text-gray-500">All the stories you've saved for later.</p>
</header>

@if($blogs->isEmpty())
    <div class="text-center py-20 glass">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
        <h3 class="text-xl font-bold text-gray-400">No saved stories yet.</h3>
        <a href="{{ route('home') }}" class="inline-block mt-4 text-zinc-900 font-semibold hover:underline">Start exploring</a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($blogs as $save)
        @php $blog = $save->blog; @endphp
        <article class="glass overflow-hidden flex flex-col group card fade-in">
            <a href="{{ route('blog.show', $blog) }}" class="block overflow-hidden">
                @if($blog->image)
                    <img src="{{ asset('storage/' . $blog->image) }}" class="w-full h-48 object-cover">
                @endif
            </a>
            <div class="p-6">
                <h2 class="text-xl font-bold mb-2">
                    <a href="{{ route('blog.show', $blog) }}" class="hover:text-zinc-900 transition-colors">{{ $blog->title }}</a>
                </h2>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-xs text-gray-400">{{ $blog->created_at->format('M d, Y') }}</span>
                    <button onclick="toggleSave({{ $blog->id }})" class="text-zinc-900 hover:text-red-500 font-bold text-sm transition-colors">Remove</button>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    <div class="mt-12">
        {{ $blogs->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
    function toggleSave(blogId) {
        fetch(`/blog/${blogId}/save`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(() => {
            window.location.reload();
        });
    }
</script>
@endpush

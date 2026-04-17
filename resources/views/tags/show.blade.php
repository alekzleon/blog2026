@extends('layouts.app')

@section('title', 'Tag: #' . $tag->name)

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Tag: <span class="text-indigo-600">#{{ $tag->name }}</span></h1>
    </div>

    @if($posts->isEmpty())
        <p class="text-gray-500">No hay publicaciones con este tag.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @endif
@endsection

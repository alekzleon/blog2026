@extends('layouts.app')

@section('title', 'Categoría: ' . $category->name)

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Categoría: <span class="text-indigo-600">{{ $category->name }}</span></h1>
        @if($category->description)
            <p class="mt-2 text-gray-500">{{ $category->description }}</p>
        @endif
    </div>

    @if($posts->isEmpty())
        <p class="text-gray-500">No hay publicaciones en esta categoría.</p>
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

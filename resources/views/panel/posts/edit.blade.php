@extends('layouts.app')

@section('title', 'Editar blog')
@section('meta_description', 'Edición administrativa del artículo.')

@section('content')
    <section class="mx-auto w-full max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 space-y-3">
            <span class="inline-flex rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-extrabold uppercase tracking-[0.24em] text-brand-orange">
                Panel IA
            </span>
            <h1 class="font-serif text-4xl font-bold text-brand-ink sm:text-5xl">Editar artículo</h1>
            <p class="max-w-2xl text-base leading-8 text-stone-600">
                Ajusta el contenido, la categoría, los tags, la imagen y el estado de publicación del blog.
            </p>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-soft">
            <form action="{{ route('panel.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="grid gap-8 px-6 py-8 md:px-8 lg:px-10">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="title" class="text-sm font-bold text-brand-ink">Título</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $post->title) }}" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-5 py-4 text-base text-stone-700 outline-none transition focus:border-brand-orange focus:bg-white" required>
                    @error('title')
                        <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="category_name" class="text-sm font-bold text-brand-ink">Categoría</label>
                        <input id="category_name" name="category_name" type="text" value="{{ old('category_name', $categoryName) }}" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-5 py-4 text-base text-stone-700 outline-none transition focus:border-brand-orange focus:bg-white">
                    </div>

                    <div class="space-y-2">
                        <label for="tags" class="text-sm font-bold text-brand-ink">Tags</label>
                        <input id="tags" name="tags" type="text" value="{{ old('tags', $tagsValue) }}" placeholder="ecommerce, anuncios, conversion" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-5 py-4 text-base text-stone-700 outline-none transition focus:border-brand-orange focus:bg-white">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="excerpt" class="text-sm font-bold text-brand-ink">Extracto</label>
                    <textarea id="excerpt" name="excerpt" rows="4" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-5 py-4 text-base text-stone-700 outline-none transition focus:border-brand-orange focus:bg-white">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>

                <div class="space-y-2">
                    <label for="content" class="text-sm font-bold text-brand-ink">Contenido HTML</label>
                    <textarea id="content" name="content" rows="18" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-5 py-4 font-mono text-sm text-stone-700 outline-none transition focus:border-brand-orange focus:bg-white" required>{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="published_at" class="text-sm font-bold text-brand-ink">Fecha de publicación</label>
                        <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\\TH:i')) }}" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-5 py-4 text-base text-stone-700 outline-none transition focus:border-brand-orange focus:bg-white">
                    </div>

                    <div class="space-y-2">
                        <label for="image" class="text-sm font-bold text-brand-ink">Nueva imagen</label>
                        <input id="image" name="image" type="file" accept="image/*" class="block w-full rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-5 py-6 text-sm text-stone-500 file:mr-4 file:rounded-full file:border-0 file:bg-brand-orange file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-stone-900">
                    </div>
                </div>

                @if($post->image)
                    <div class="space-y-4 rounded-[1.5rem] border border-stone-200 bg-stone-50 px-5 py-5">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="h-56 w-full rounded-[1.25rem] object-cover md:h-64">
                        <label class="flex items-center gap-3 text-sm text-stone-600">
                            <input type="checkbox" name="remove_image" value="1" class="h-4 w-4 rounded border-stone-300 text-brand-orange focus:ring-brand-orange">
                            Eliminar imagen actual
                        </label>
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-4">
                    <button type="submit" class="inline-flex items-center rounded-full bg-brand-orange px-6 py-3 text-sm font-bold text-white transition hover:bg-stone-900">
                        Guardar cambios
                    </button>
                    <a href="{{ route('panel.posts.show', $post) }}" class="text-sm font-bold text-stone-500 transition hover:text-brand-orange">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', 'Vista administrativa del artículo.')

@section('content')
    <section class="mx-auto w-full max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div class="space-y-3">
                <span class="inline-flex rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-extrabold uppercase tracking-[0.24em] text-brand-orange">
                    Panel IA
                </span>
                <h1 class="max-w-4xl font-serif text-4xl font-bold leading-tight text-brand-ink sm:text-5xl">{{ $post->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-sm text-stone-500">
                    <span>{{ optional($post->category)->name ?? 'Sin categoría' }}</span>
                    <span>{{ optional($post->published_at)->translatedFormat('d M Y, H:i') ?? 'Borrador' }}</span>
                    <span>{{ $post->views_count }} vistas</span>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('posts.show', $post) }}" target="_blank" class="rounded-full border border-stone-300 px-5 py-3 text-sm font-bold text-stone-600 transition hover:border-brand-orange hover:text-brand-orange">Ver público</a>
                <a href="{{ route('panel.posts.edit', $post) }}" class="rounded-full bg-brand-orange px-5 py-3 text-sm font-bold text-white transition hover:bg-stone-900">Editar</a>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,0.72fr)_minmax(280px,0.28fr)]">
            <div class="rounded-[2rem] bg-white px-6 py-8 shadow-soft sm:px-8">
                @if($post->image)
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="mb-8 h-72 w-full rounded-[1.75rem] object-cover">
                @endif

                @if($post->excerpt)
                    <div class="mb-8 rounded-[1.5rem] border border-orange-100 bg-brand-coral/20 px-5 py-5">
                        <p class="text-lg italic leading-8 text-stone-600">{{ $post->excerpt }}</p>
                    </div>
                @endif

                <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-brand-ink prose-p:text-stone-700 prose-li:text-stone-700 prose-strong:text-brand-ink">
                    {!! $post->content !!}
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-soft">
                    <h2 class="font-serif text-2xl font-bold text-brand-ink">Datos del artículo</h2>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div>
                            <dt class="font-bold text-stone-500">Slug</dt>
                            <dd class="mt-1 break-all text-brand-ink">{{ $post->slug }}</dd>
                        </div>
                        <div>
                            <dt class="font-bold text-stone-500">Creado</dt>
                            <dd class="mt-1 text-brand-ink">{{ $post->created_at->translatedFormat('d M Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="font-bold text-stone-500">Actualizado</dt>
                            <dd class="mt-1 text-brand-ink">{{ $post->updated_at->translatedFormat('d M Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="font-bold text-stone-500">Tags</dt>
                            <dd class="mt-2 flex flex-wrap gap-2">
                                @forelse($post->tags as $tag)
                                    <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-600">#{{ $tag->name }}</span>
                                @empty
                                    <span class="text-stone-400">Sin tags</span>
                                @endforelse
                            </dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </div>
    </section>
@endsection

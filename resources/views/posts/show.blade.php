@extends('layouts.app')

@section('title', $post->title)
@php
    $seoDescription = $post->excerpt
        ? \Illuminate\Support\Str::limit(trim(strip_tags($post->excerpt)), 160)
        : \Illuminate\Support\Str::limit(trim(strip_tags($post->content)), 160);
    $canonicalUrl = route('posts.show', $post);
    $ogImage = $post->image_url ?: asset('default-og.svg');
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->title,
        'description' => $seoDescription,
        'datePublished' => optional($post->published_at)->toIso8601String(),
        'dateModified' => $post->updated_at->toIso8601String(),
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $canonicalUrl,
        ],
        'image' => [$ogImage],
        'articleSection' => optional($post->category)->name,
        'keywords' => $post->tags->pluck('name')->implode(', '),
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Mi Blog',
            'url' => url('/'),
        ],
    ];
@endphp
@section('meta_description', $seoDescription)
@section('canonical_url', $canonicalUrl)
@section('og_type', 'article')
@section('og_title', $post->title . ' — Mi Blog')
@section('og_description', $seoDescription)
@section('og_image', $ogImage)
@section('og_image_alt', $post->title)
@section('twitter_title', $post->title . ' — Mi Blog')
@section('twitter_description', $seoDescription)

@push('head')
    <style>
        .article-shell {
            position: relative;
        }

        .article-shell::before {
            content: "";
            position: absolute;
            inset: 3rem auto auto 50%;
            z-index: 0;
            height: 32rem;
            width: min(90vw, 58rem);
            transform: translateX(-50%);
            border-radius: 9999px;
            background: radial-gradient(circle, rgba(255, 106, 26, 0.12) 0%, rgba(255, 106, 26, 0) 68%);
            pointer-events: none;
        }

        .article-body {
            position: relative;
            z-index: 1;
        }

        .article-body > * + * {
            margin-top: 1.5rem;
        }

        .article-body h2,
        .article-body h3 {
            font-family: 'Source Serif 4', ui-serif, Georgia, serif;
            color: #221f1a;
            letter-spacing: -0.03em;
        }

        .article-body h2 {
            margin-top: 3.75rem;
            font-size: clamp(2rem, 3vw, 2.75rem);
            line-height: 1.05;
        }

        .article-body h3 {
            margin-top: 2.5rem;
            font-size: clamp(1.35rem, 2vw, 1.7rem);
            line-height: 1.2;
        }

        .article-body p,
        .article-body li {
            font-size: 1.08rem;
            line-height: 1.95;
            color: #4f4a43;
        }

        .article-body p {
            max-width: 70ch;
        }

        .article-body strong {
            color: #221f1a;
            font-weight: 800;
        }

        .article-body ul,
        .article-body ol {
            margin-top: 1.75rem;
            padding-left: 0;
            list-style: none;
        }

        .article-body ul li,
        .article-body ol li {
            position: relative;
            margin-top: 1rem;
            padding-left: 1.75rem;
        }

        .article-body ul li::before,
        .article-body ol li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.9rem;
            height: 0.55rem;
            width: 0.55rem;
            border-radius: 9999px;
            background: #ff6a1a;
            box-shadow: 0 0 0 6px rgba(255, 106, 26, 0.12);
        }

        .article-body blockquote {
            margin-top: 2.5rem;
            max-width: 62ch;
            border: 0;
            border-left: 4px solid #ff6a1a;
            border-radius: 0 1.5rem 1.5rem 0;
            background: linear-gradient(135deg, rgba(255, 237, 227, 0.9), rgba(255, 255, 255, 0.9));
            padding: 1.4rem 1.5rem 1.4rem 1.75rem;
            font-family: 'Source Serif 4', ui-serif, Georgia, serif;
            font-size: 1.4rem;
            line-height: 1.45;
            color: #2a251f;
            box-shadow: 0 18px 40px rgba(34, 31, 26, 0.06);
        }

        .article-body blockquote p {
            margin: 0;
            color: inherit;
            font-size: inherit;
            line-height: inherit;
        }
    </style>
    <meta property="article:published_time" content="{{ optional($post->published_at)->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    @if($post->category)
        <meta property="article:section" content="{{ $post->category->name }}">
    @endif
    @foreach($post->tags as $tag)
        <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach
    <script type="application/ld+json">
        {!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    <article class="article-shell mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if($post->image)
            <div class="relative z-10 mb-10 flex h-72 w-full items-center justify-center overflow-hidden rounded-[2rem] shadow-soft md:h-[30rem]">
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="h-full w-full object-contain">
            </div>
        @endif

        <div class="relative z-10 mx-auto mb-3 flex max-w-3xl items-center gap-3 text-sm text-stone-400">
            @if($post->category)
                <a href="{{ route('categories.show', $post->category) }}" class="font-semibold text-brand-orange hover:underline">
                    {{ $post->category->name }}
                </a>
                <span>&middot;</span>
            @endif
            <span>{{ $post->published_at->translatedFormat('d M, Y') }}</span>
        </div>

        <h1 class="relative z-10 mx-auto mb-6 max-w-4xl font-serif text-4xl font-bold leading-[0.98] text-brand-ink md:text-6xl">{{ $post->title }}</h1>

        @if($post->excerpt)
            <div class="relative z-10 mx-auto mb-12 max-w-3xl rounded-[1.75rem] border border-orange-100 bg-white/90 px-6 py-6 shadow-soft">
                <p class="border-l-4 border-orange-200 pl-4 text-xl italic leading-9 text-stone-500">{{ $post->excerpt }}</p>
            </div>
        @endif

        <div class="article-body mx-auto max-w-3xl rounded-[2rem] bg-white px-6 py-8 shadow-soft sm:px-8 md:px-10 md:py-10">
            {!! $post->content !!}
        </div>

        @if($post->tags->count())
            <div class="mx-auto mt-8 flex max-w-3xl flex-wrap gap-2">
                @foreach($post->tags as $tag)
                    <a href="{{ route('tags.show', $tag) }}" class="rounded-full bg-stone-100 px-3 py-1 text-sm text-stone-500 hover:bg-brand-coral hover:text-brand-orange">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </article>

    @if($related->count())
        <section class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="mb-6 font-serif text-3xl font-bold text-brand-ink">Artículos relacionados</h2>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                @foreach($related as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif
@endsection

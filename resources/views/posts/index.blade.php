@extends('layouts.app')

@section('title', 'Blog')
@section('meta_description', 'Descubre artículos recientes, recursos y tendencias para ecommerce, marketing y automatización.')

@section('content')
    <section class="border-b border-stone-200 bg-[radial-gradient(circle_at_top_left,_rgba(255,106,26,0.16),_transparent_28%),linear-gradient(180deg,_#fffdf9_0%,_#f6f1ea_100%)]">
        <div class="mx-auto grid w-full max-w-7xl gap-10 px-4 py-12 sm:px-6 md:py-16 lg:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)] lg:items-center lg:px-8 lg:py-20">
            <div class="space-y-6">
                <span class="inline-flex items-center rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-extrabold uppercase tracking-[0.24em] text-brand-orange">
                    Último artículo publicado
                </span>

                @if($featuredPost)
                    @if($featuredPost->category)
                        <a href="{{ route('categories.show', $featuredPost->category) }}" class="inline-flex text-sm font-bold uppercase tracking-[0.2em] text-stone-500 transition hover:text-brand-orange">
                            {{ $featuredPost->category->name }}
                        </a>
                    @endif

                    <div class="space-y-5">
                        <h1 class="max-w-4xl font-serif text-4xl font-bold leading-tight text-brand-ink sm:text-5xl lg:text-6xl">
                            <a href="{{ route('posts.show', $featuredPost) }}" class="transition hover:text-brand-orange">
                                {{ $featuredPost->title }}
                            </a>
                        </h1>

                        <p class="max-w-2xl text-base leading-8 text-stone-600 sm:text-lg">
                            {{ $featuredPost->excerpt }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-stone-500">
                        <span>{{ $featuredPost->published_at->translatedFormat('d M Y') }}</span>
                        @if($featuredPost->tags->isNotEmpty())
                            <span class="h-1 w-1 rounded-full bg-stone-300"></span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($featuredPost->tags->take(3) as $tag)
                                    <a href="{{ route('tags.show', $tag) }}" class="rounded-full bg-white px-3 py-1 font-semibold text-stone-600 transition hover:bg-brand-coral hover:text-brand-orange">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div>
                        <a href="{{ route('posts.show', $featuredPost) }}" class="inline-flex items-center rounded-full bg-brand-orange px-6 py-3 text-sm font-bold text-white transition hover:bg-stone-900">
                            Leer artículo
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        <h1 class="font-serif text-4xl font-bold leading-tight text-brand-ink sm:text-5xl">
                            Tu próximo artículo destacado aparecerá aquí.
                        </h1>
                        <p class="max-w-2xl text-base leading-8 text-stone-600 sm:text-lg">
                            Cuando publiques contenido nuevo, este espacio mostrará automáticamente el post más reciente con una presentación principal.
                        </p>
                    </div>
                @endif
            </div>

            <div class="relative">
                <div class="absolute inset-0 translate-x-4 translate-y-4 rounded-[2rem] bg-brand-orange/10"></div>
                <div class="relative overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-soft">
                    @if($featuredPost && $featuredPost->image)
                        <img src="{{ asset('storage/' . $featuredPost->image) }}" alt="{{ $featuredPost->title }}" class="h-full min-h-[320px] w-full object-cover">
                    @else
                        <div class="flex min-h-[320px] items-center justify-center bg-[linear-gradient(135deg,_#ffede3_0%,_#fff7f1_55%,_#f6f1ea_100%)] p-10">
                            <div class="max-w-sm space-y-4 text-center">
                                <span class="inline-flex rounded-full bg-white px-4 py-1 text-xs font-extrabold uppercase tracking-[0.22em] text-brand-orange">
                                    Hero visual
                                </span>
                                <p class="font-serif text-3xl font-bold leading-tight text-brand-ink">
                                    Agrega una imagen destacada para que este espacio se vea como portada editorial.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-soft">
            <div class="grid gap-6 px-6 py-8 md:grid-cols-[minmax(0,1fr)_auto] md:items-center md:px-8 lg:px-10">
                <div class="space-y-3">
                    <span class="text-xs font-extrabold uppercase tracking-[0.24em] text-brand-orange">Newsletter</span>
                    <h2 class="font-serif text-3xl font-bold text-brand-ink">Recibe nuevos artículos directo en tu correo.</h2>
                    <p class="max-w-2xl text-stone-600">
                        Déjanos tu correo y te avisaremos cuando publiquemos nuevos artículos, recursos y tendencias de ecommerce, marketing, ventas e <strong>Inteligencia Artificial</strong> .
                    </p>
                </div>

                <form id="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" class="flex w-full flex-col gap-3 sm:flex-row md:w-auto">
                    @csrf
                    <label for="newsletter-email" class="sr-only">Correo electrónico</label>
                    <input
                        id="newsletter-email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="tu@email.com"
                        class="min-w-[260px] rounded-full border border-stone-300 bg-stone-50 px-5 py-3 text-sm text-stone-700 outline-none transition placeholder:text-stone-400 focus:border-brand-orange focus:bg-white"
                        required
                    >
                    <button id="newsletter-submit" type="submit" class="inline-flex items-center justify-center gap-3 rounded-full bg-brand-orange px-6 py-3 text-sm font-bold text-white transition hover:bg-stone-900 disabled:cursor-not-allowed disabled:bg-stone-400">
                        <svg id="newsletter-spinner" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity="0.25" stroke-width="3"></circle>
                            <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path>
                        </svg>
                        <span id="newsletter-submit-text">Suscribirse</span>
                    </button>
                </form>
            </div>

            @if(session('newsletter_status') || $errors->has('email'))
                <div class="border-t border-stone-200 px-6 py-4 md:px-8 lg:px-10">
                    @if(session('newsletter_status'))
                        <p class="text-sm font-medium text-emerald-700">{{ session('newsletter_status') }}</p>
                    @endif

                    @error('email')
                        <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 pb-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div class="space-y-2">
                <span class="text-xs font-extrabold uppercase tracking-[0.24em] text-brand-orange">Artículos recientes</span>
                <h2 class="font-serif text-3xl font-bold text-brand-ink">Explora las últimas publicaciones</h2>
            </div>

            <a href="{{ route('posts.index') }}" class="text-sm font-bold text-stone-500 transition hover:text-brand-orange">
                Ver todo el blog
            </a>
        </div>

        @if($featuredPost || $posts->isNotEmpty())
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @else
            <div class="rounded-[2rem] border border-dashed border-stone-300 bg-white px-6 py-16 text-center shadow-soft">
                <h2 class="font-serif text-3xl font-bold text-brand-ink">Aún no hay publicaciones</h2>
                <p class="mx-auto mt-3 max-w-2xl text-stone-500">
                    Cuando empieces a subir artículos, aquí aparecerán en formato de revista con el más reciente destacado arriba.
                </p>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('newsletter-form');
            const submitButton = document.getElementById('newsletter-submit');
            const submitText = document.getElementById('newsletter-submit-text');
            const spinner = document.getElementById('newsletter-spinner');
            const emailInput = document.getElementById('newsletter-email');

            if (!form || !submitButton || !submitText || !spinner || !emailInput) {
                return;
            }

            let submitting = false;

            form.addEventListener('submit', function (event) {
                if (submitting) {
                    event.preventDefault();
                    return;
                }

                submitting = true;
                submitButton.disabled = true;
                spinner.classList.remove('hidden');
                submitText.textContent = 'Enviando...';
                emailInput.readOnly = true;
                form.classList.add('opacity-80');
            });
        })();
    </script>
@endpush

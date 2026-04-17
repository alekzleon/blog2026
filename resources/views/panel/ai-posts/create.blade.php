@extends('layouts.app')

@section('title', 'Panel IA')
@section('meta_description', 'Panel para generar articulos con inteligencia artificial y publicarlos automaticamente.')

@section('content')
    <section class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 space-y-3">
            <span class="inline-flex rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-extrabold uppercase tracking-[0.24em] text-brand-orange">
                Panel IA
            </span>
            <h1 class="font-serif text-4xl font-bold text-brand-ink sm:text-5xl">Generar un post automaticamente</h1>
            <p class="max-w-2xl text-base leading-8 text-stone-600">
                Escribe solo el tema o título del artículo. El sistema generará el contenido en HTML para renderizarlo directamente en el post y asignará una imagen subida, generada por IA o una portada por defecto.
            </p>
        </div>

        <div class="relative overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-soft">
            <div
                id="ai-post-loading"
                class="pointer-events-none absolute inset-0 z-20 flex items-center justify-center bg-white/75 opacity-0 backdrop-blur-sm transition duration-300"
                aria-hidden="true"
            >
                <div class="mx-6 max-w-md rounded-[1.75rem] border border-orange-200 bg-white px-8 py-7 text-center shadow-soft">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-brand-coral text-brand-orange">
                        <svg class="h-7 w-7 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity="0.25" stroke-width="3"></circle>
                            <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path>
                        </svg>
                    </div>
                    <h2 class="mt-5 font-serif text-2xl font-bold text-brand-ink">Generando artículo</h2>
                    <p class="mt-3 text-sm leading-7 text-stone-600">
                        Estamos creando el contenido y preparando la imagen. Este proceso puede tardar un poco, especialmente si la portada se genera con IA.
                    </p>
                </div>
            </div>

            <form id="ai-post-form" action="{{ route('panel.ai-posts.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-8 px-6 py-8 md:px-8 lg:px-10">
                @csrf

                <div class="space-y-2">
                    <label for="topic" class="text-sm font-bold text-brand-ink">Tema o título</label>
                    <input
                        id="topic"
                        name="topic"
                        type="text"
                        value="{{ old('topic') }}"
                        placeholder="Escribe un tema"
                        class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-5 py-4 text-base text-stone-700 outline-none transition placeholder:text-stone-400 focus:border-brand-orange focus:bg-white"
                        required
                    >
                    @error('topic')
                        <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="image" class="text-sm font-bold text-brand-ink">Imagen opcional</label>
                    <input
                        id="image"
                        name="image"
                        type="file"
                        accept="image/*"
                        class="block w-full rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-5 py-6 text-sm text-stone-500 file:mr-4 file:rounded-full file:border-0 file:bg-brand-orange file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-stone-900"
                    >
                    <p class="text-sm text-stone-500">
                        Si no subes una imagen, intentaremos generarla con IA. Si eso no está disponible, guardaremos una portada por defecto en `public/storage`.
                    </p>
                    @error('image')
                        <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-[1.5rem] bg-brand-coral/50 px-5 py-4 text-sm text-stone-600">
                    El artículo se publicará automáticamente al terminar la generación.
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <button
                        id="ai-post-submit"
                        type="submit"
                        class="inline-flex items-center gap-3 rounded-full bg-brand-orange px-6 py-3 text-sm font-bold text-white transition hover:bg-stone-900 disabled:cursor-not-allowed disabled:bg-stone-400"
                    >
                        <svg id="ai-post-spinner" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity="0.25" stroke-width="3"></circle>
                            <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path>
                        </svg>
                        <span id="ai-post-submit-text">Generar y publicar</span>
                    </button>
                    <a href="{{ route('home') }}" class="text-sm font-bold text-stone-500 transition hover:text-brand-orange">Volver al blog</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('ai-post-form');
            const overlay = document.getElementById('ai-post-loading');
            const submitButton = document.getElementById('ai-post-submit');
            const submitText = document.getElementById('ai-post-submit-text');
            const spinner = document.getElementById('ai-post-spinner');

            if (!form || !overlay || !submitButton || !submitText || !spinner) {
                return;
            }

            let submitting = false;

            form.addEventListener('submit', function (event) {
                if (submitting) {
                    event.preventDefault();
                    return;
                }

                submitting = true;
                overlay.classList.remove('pointer-events-none', 'opacity-0');
                overlay.classList.add('pointer-events-auto', 'opacity-100');
                spinner.classList.remove('hidden');
                submitButton.disabled = true;
                submitText.textContent = 'Generando artículo...';

                const topicInput = form.querySelector('#topic');
                const imageInput = form.querySelector('#image');

                if (topicInput) {
                    topicInput.readOnly = true;
                }

                if (imageInput) {
                    imageInput.classList.add('pointer-events-none', 'opacity-60');
                }
            });
        })();
    </script>
@endpush

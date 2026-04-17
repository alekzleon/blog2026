@extends('layouts.app')

@section('title', 'Blogs')
@section('meta_description', 'Panel de administración de artículos del blog.')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="space-y-3">
                <span class="inline-flex rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-extrabold uppercase tracking-[0.24em] text-brand-orange">
                    Panel IA
                </span>
                <h1 class="font-serif text-4xl font-bold text-brand-ink sm:text-5xl">Gestión de blogs</h1>
                <p class="max-w-2xl text-base leading-8 text-stone-600">
                    Revisa el contenido creado, entra al detalle, edítalo o elimínalo completamente cuando lo necesites.
                </p>
            </div>

            <a href="{{ route('panel.ai-posts.create') }}" class="inline-flex items-center justify-center rounded-full bg-brand-orange px-6 py-3 text-sm font-bold text-white transition hover:bg-stone-900">
                Crear post
            </a>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-soft">
            @if($posts->isEmpty())
                <div class="px-6 py-16 text-center sm:px-8">
                    <h2 class="font-serif text-3xl font-bold text-brand-ink">Aún no hay artículos</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-stone-500">
                        Cuando publiques o generes artículos desde el panel, aparecerán aquí con sus estadísticas básicas.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-[0.18em] text-stone-500 sm:px-8">Artículo</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-[0.18em] text-stone-500 sm:px-8">Categoría</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-[0.18em] text-stone-500 sm:px-8">Publicado</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-[0.18em] text-stone-500 sm:px-8">Vistas</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-[0.18em] text-stone-500 sm:px-8">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach($posts as $post)
                                <tr class="align-top transition hover:bg-brand-coral/20">
                                    <td class="px-6 py-5 sm:px-8">
                                        <div class="max-w-xl">
                                            <p class="text-base font-bold text-brand-ink">{{ $post->title }}</p>
                                            @if($post->excerpt)
                                                <p class="mt-2 text-sm leading-7 text-stone-500">{{ \Illuminate\Support\Str::limit($post->excerpt, 140) }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-stone-500 sm:px-8">{{ optional($post->category)->name ?? 'Sin categoría' }}</td>
                                    <td class="px-6 py-5 text-sm text-stone-500 sm:px-8">{{ optional($post->published_at)->translatedFormat('d M Y, H:i') ?? 'Borrador' }}</td>
                                    <td class="px-6 py-5 text-sm font-semibold text-brand-ink sm:px-8">{{ $post->views_count }}</td>
                                    <td class="px-6 py-5 sm:px-8">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('panel.posts.show', $post) }}" class="rounded-full border border-stone-300 px-4 py-2 text-xs font-bold uppercase tracking-[0.12em] text-stone-600 transition hover:border-brand-orange hover:text-brand-orange">Ver</a>
                                            <a href="{{ route('panel.posts.edit', $post) }}" class="rounded-full border border-stone-300 px-4 py-2 text-xs font-bold uppercase tracking-[0.12em] text-stone-600 transition hover:border-brand-orange hover:text-brand-orange">Editar</a>
                                            <form action="{{ route('panel.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este artículo? Esta acción no se puede deshacer.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-full border border-red-200 px-4 py-2 text-xs font-bold uppercase tracking-[0.12em] text-red-600 transition hover:bg-red-50">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-stone-200 px-6 py-5 sm:px-8">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

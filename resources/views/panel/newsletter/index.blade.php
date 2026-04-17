@extends('layouts.app')

@section('title', 'Suscritos')
@section('meta_description', 'Listado de correos suscritos al newsletter.')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="space-y-3">
                <span class="inline-flex rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-extrabold uppercase tracking-[0.24em] text-brand-orange">
                    Panel IA
                </span>
                <h1 class="font-serif text-4xl font-bold text-brand-ink sm:text-5xl">Suscritos al newsletter</h1>
                <p class="max-w-2xl text-base leading-8 text-stone-600">
                    Aquí puedes revisar los correos registrados y descargar la lista para abrirla en Excel.
                </p>
            </div>

            <a href="{{ route('panel.newsletter.export') }}" class="inline-flex items-center justify-center rounded-full bg-brand-orange px-6 py-3 text-sm font-bold text-white transition hover:bg-stone-900">
                Descargar Excel
            </a>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-soft">
            @if($subscribers->isEmpty())
                <div class="px-6 py-16 text-center sm:px-8">
                    <h2 class="font-serif text-3xl font-bold text-brand-ink">Aún no hay suscriptores</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-stone-500">
                        Cuando alguien se suscriba al newsletter, aparecerá aquí con su correo y fecha de registro.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-[0.18em] text-stone-500 sm:px-8">Correo</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-[0.18em] text-stone-500 sm:px-8">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach($subscribers as $subscriber)
                                <tr class="transition hover:bg-brand-coral/20">
                                    <td class="px-6 py-5 text-sm font-semibold text-brand-ink sm:px-8">{{ $subscriber->email }}</td>
                                    <td class="px-6 py-5 text-sm text-stone-500 sm:px-8">
                                        {{ optional($subscriber->subscribed_at)->translatedFormat('d M Y, H:i') ?? 'Sin fecha' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-stone-200 px-6 py-5 sm:px-8">
                    {{ $subscribers->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

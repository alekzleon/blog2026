@extends('layouts.app')

@section('title', 'Iniciar sesión')
@section('meta_description', 'Acceso privado al panel de generación de artículos.')

@section('content')
    <section class="mx-auto flex min-h-[70vh] w-full max-w-7xl items-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid w-full gap-10 lg:grid-cols-[minmax(0,0.95fr)_minmax(320px,0.85fr)] lg:items-center">
            <div class="space-y-6">
                <span class="inline-flex rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-extrabold uppercase tracking-[0.24em] text-brand-orange">
                    Acceso privado
                </span>
                <h1 class="max-w-3xl font-serif text-4xl font-bold leading-[0.98] text-brand-ink sm:text-5xl lg:text-6xl">
                    Configura y crea articulos con IA
                </h1>
                <p class="max-w-2xl text-lg leading-8 text-stone-600">
                    Solo los usuarios autenticados pueden generar y publicar artículos automáticamente desde el panel, si quieres un blog para tu empresa manda un correo a hola@cloudi.mx
                </p>
            </div>

            <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-soft">
                <form action="{{ route('login.store') }}" method="POST" class="space-y-6 px-6 py-8 sm:px-8">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-bold text-brand-ink">Correo electrónico</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-5 py-4 text-base text-stone-700 outline-none transition focus:border-brand-orange focus:bg-white"
                            required
                            autofocus
                        >
                        @error('email')
                            <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-bold text-brand-ink">Contraseña</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            value=""
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-5 py-4 text-base text-stone-700 outline-none transition focus:border-brand-orange focus:bg-white"
                            required
                        >
                    </div>

                    <label class="flex items-center gap-3 text-sm text-stone-500">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-stone-300 text-brand-orange focus:ring-brand-orange">
                        Mantener sesión iniciada
                    </label>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-brand-orange px-6 py-3 text-sm font-bold text-white transition hover:bg-stone-900">
                        Entrar al panel
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection

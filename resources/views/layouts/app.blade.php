<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Blog') — Mi Blog</title>
    <meta name="description" content="@yield('meta_description', 'Artículos, ideas y recursos para crecer tu marca y tu ecommerce.')">
    <meta name="robots" content="@yield('meta_robots', 'index,follow')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    <meta property="og:locale" content="es_MX">
    <meta property="og:site_name" content="Mi Blog">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', 'Blog') . ' — Mi Blog'))">
    <meta property="og:description" content="@yield('og_description', $__env->yieldContent('meta_description', 'Artículos, ideas y recursos para crecer tu marca y tu ecommerce.'))">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('default-og.svg'))">
    <meta property="og:image:alt" content="@yield('og_image_alt', trim($__env->yieldContent('title', 'Blog') . ' — Mi Blog'))">
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', trim($__env->yieldContent('title', 'Blog') . ' — Mi Blog'))">
    <meta name="twitter:description" content="@yield('twitter_description', $__env->yieldContent('meta_description', 'Artículos, ideas y recursos para crecer tu marca y tu ecommerce.'))">
    <meta name="twitter:image" content="@yield('og_image', asset('default-og.svg'))">
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Source+Serif+4:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        serif: ['Source Serif 4', 'ui-serif', 'Georgia', 'serif'],
                    },
                    colors: {
                        brand: {
                            cream: '#f6f1ea',
                            sand: '#efe5d6',
                            ink: '#221f1a',
                            orange: '#ff6a1a',
                            coral: '#ffede3',
                            cloudiPink: '#ff2768',
                            cloudiNavy: '#1c1f33',
                        },
                    },
                    boxShadow: {
                        soft: '0 18px 45px rgba(34, 31, 26, 0.08)',
                    },
                },
            },
        };
    </script>
    <style>
        .site-logo {
            letter-spacing: -0.04em;
        }

        .site-logo--header {
            display: inline-flex;
            align-items: center;
            line-height: 1;
        }

        .site-logo--footer {
            font-size: 1rem;
            line-height: 1;
        }

        .footer-social-icon {
            transition: transform 180ms ease, color 180ms ease, border-color 180ms ease, background-color 180ms ease;
        }

        .footer-social-icon:hover {
            transform: translateY(-2px);
        }

        .app-toast {
            transform: translateY(0);
            opacity: 1;
            transition: opacity 220ms ease, transform 220ms ease;
        }

        .app-toast.is-leaving {
            transform: translateY(-8px);
            opacity: 0;
        }
    </style>
    @stack('head')
</head>
<body class="flex min-h-screen flex-col bg-brand-cream text-brand-ink">

    <header class="sticky top-0 z-40 border-b border-stone-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="site-logo site-logo--header font-extrabold text-brand-ink transition hover:text-brand-orange">
                <img src="{{ asset('logo_v.png') }}" alt="Cloudi" class="h-10 w-auto sm:h-11">
            </a>

            <nav class="hidden items-center gap-8 text-sm font-semibold text-stone-700 md:flex">
                @auth
                    <details class="group relative">
                        <summary class="flex cursor-pointer list-none items-center gap-2 rounded-full border border-stone-200 bg-stone-50 px-4 py-2 transition hover:border-brand-orange hover:text-brand-orange">
                            Panel IA
                            <svg class="h-4 w-4 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </summary>

                        <div class="absolute right-0 mt-3 w-64 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-soft">
                            <a href="{{ route('panel.ai-posts.create') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Crear post</a>
                            <a href="{{ route('panel.newsletter.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Ver suscritos</a>
                            <a href="{{ route('panel.posts.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Ver blogs</a>
                        </div>
                    </details>
                @endauth

                <a href="{{ route('home') }}" class="transition hover:text-brand-orange">Blog</a>
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="transition hover:text-brand-orange">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="transition hover:text-brand-orange">Entrar</a>
                @endauth
            </nav>

            <details class="group relative md:hidden">
                <summary class="flex cursor-pointer list-none items-center gap-2 rounded-full border border-stone-200 bg-white px-4 py-2 text-sm font-semibold text-stone-700 transition hover:border-brand-orange hover:text-brand-orange">
                    @auth Panel IA @else Menú @endauth
                    <svg class="h-4 w-4 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </summary>

                <div class="absolute right-0 mt-3 w-64 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-soft">
                    @auth
                        <a href="{{ route('panel.ai-posts.create') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Crear post</a>
                        <a href="{{ route('panel.newsletter.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Ver suscritos</a>
                        <a href="{{ route('panel.posts.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Ver blogs</a>
                        <div class="my-2 border-t border-stone-200"></div>
                        <a href="{{ route('home') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Inicio</a>
                        <form action="{{ route('logout') }}" method="POST" class="px-4 py-2">
                            @csrf
                            <button type="submit" class="block w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Salir</button>
                        </form>
                    @else
                        <a href="{{ route('home') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Blog</a>
                        <a href="{{ route('login') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Entrar</a>
                    @endauth
                    <a href="#footer" class="block rounded-xl px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-brand-coral hover:text-brand-orange">Contacto</a>
                </div>
            </details>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer id="footer" class="mt-16 bg-brand-cloudiNavy text-brand-cloudiPink">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-10 px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                <div class="space-y-5">
                    <a href="{{ route('home') }}" class="inline-flex items-center">
                        <img src="{{ asset('logo_v.png') }}" alt="Cloudi" class="h-12 w-auto sm:h-14">
                    </a>
                    <p class="max-w-2xl font-serif text-3xl font-bold leading-tight text-brand-cloudiPink sm:text-4xl">
                        Entiende a tu cliente
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <a href="https://www.instagram.com/cloudigdl" target="_blank" rel="noopener noreferrer" class="footer-social-icon inline-flex h-12 w-12 items-center justify-center rounded-full border border-brand-cloudiPink/40 text-brand-cloudiPink hover:border-brand-cloudiPink hover:bg-brand-cloudiPink hover:text-brand-cloudiNavy" aria-label="Instagram">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3.5" y="3.5" width="17" height="17" rx="4"></rect>
                            <circle cx="12" cy="12" r="3.75"></circle>
                            <circle cx="17.5" cy="6.5" r="1"></circle>
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/cloudigdl" target="_blank" rel="noopener noreferrer" class="footer-social-icon inline-flex h-12 w-12 items-center justify-center rounded-full border border-brand-cloudiPink/40 text-brand-cloudiPink hover:border-brand-cloudiPink hover:bg-brand-cloudiPink hover:text-brand-cloudiNavy" aria-label="Facebook">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.5 21v-8.06h2.73l.41-3.15H13.5V7.78c0-.91.26-1.53 1.57-1.53H16.8V3.43c-.3-.04-1.33-.13-2.53-.13-2.5 0-4.22 1.53-4.22 4.34v2.15H7.2v3.15h2.85V21h3.45Z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="flex flex-col gap-5 border-t border-brand-cloudiPink/25 pt-6 text-base text-brand-cloudiPink/85 md:flex-row md:items-center md:justify-between">
                <p class="text-base leading-7 sm:text-lg">
                    &copy; {{ date('Y') }} Todos los derechos reservados. Desarrollado por <a href="https://cloudi.mx" target="_blank" rel="noopener noreferrer" class="font-semibold text-brand-cloudiPink transition hover:opacity-80">cloudi</a>.
                </p>
                <div class="flex items-center gap-6 text-base sm:text-lg">
                    <a href="#" class="transition hover:text-brand-cloudiPink">Términos</a>
                    <a href="#" class="transition hover:text-brand-cloudiPink">Aviso de privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    @php
        $toast = session('toast');
        $cookieConsent = request()->cookie('cloudi_cookie_consent');
    @endphp

    @if(!$cookieConsent)
        <div id="cookie-banner" class="fixed inset-x-0 bottom-4 z-[75] hidden px-4">
            <div class="mx-auto flex w-full max-w-4xl flex-col gap-4 rounded-[1.75rem] border border-stone-200 bg-white/95 px-5 py-5 shadow-soft backdrop-blur md:flex-row md:items-center md:justify-between md:px-6">
                <div class="space-y-2">
                    <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-brand-orange">Cookies</p>
                    <p class="max-w-2xl text-sm leading-7 text-stone-600 sm:text-base">
                        Usamos cookies para medir visitas a los artículos y mejorar la experiencia del sitio. Puedes aceptar para activar estas mediciones.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" id="cookie-reject" class="rounded-full border border-stone-300 px-5 py-3 text-sm font-bold text-stone-600 transition hover:border-stone-400 hover:text-stone-900">
                        Ahora no
                    </button>
                    <button type="button" id="cookie-accept" class="rounded-full bg-brand-orange px-5 py-3 text-sm font-bold text-white transition hover:bg-stone-900">
                        Aceptar cookies
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($toast)
        <div id="app-toast" class="pointer-events-none fixed inset-x-0 top-24 z-[80] flex justify-center px-4">
            <div class="app-toast pointer-events-auto flex w-full max-w-md items-start gap-3 rounded-2xl border px-5 py-4 shadow-soft
                {{ ($toast['type'] ?? 'success') === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : '' }}
                {{ ($toast['type'] ?? '') === 'info' ? 'border-sky-200 bg-sky-50 text-sky-800' : '' }}
                {{ ($toast['type'] ?? '') === 'error' ? 'border-red-200 bg-red-50 text-red-800' : '' }}">
                <div class="mt-0.5">
                    @if(($toast['type'] ?? 'success') === 'success')
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.2 7.262a1 1 0 0 1-1.424-.008L3.29 9.087a1 1 0 1 1 1.42-1.407l3.083 3.113 6.49-6.544a1 1 0 0 1 1.421-.007Z" clip-rule="evenodd" />
                        </svg>
                    @elseif(($toast['type'] ?? '') === 'info')
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0Zm-7-3a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-2 2a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0v-4a1 1 0 0 0-1-1Z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.707-10.707a1 1 0 0 0-1.414-1.414L10 8.172 7.707 5.879A1 1 0 0 0 6.293 7.293L8.586 9.586l-2.293 2.293a1 1 0 1 0 1.414 1.414L10 11l2.293 2.293a1 1 0 0 0 1.414-1.414L11.414 9.586l2.293-2.293Z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
                <p class="flex-1 text-sm font-medium">{{ $toast['message'] ?? '' }}</p>
                <button type="button" id="app-toast-close" class="text-current/70 transition hover:text-current" aria-label="Cerrar notificación">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 1 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @stack('scripts')
    @if(!$cookieConsent)
        <script>
            (() => {
                const banner = document.getElementById('cookie-banner');
                const acceptButton = document.getElementById('cookie-accept');
                const rejectButton = document.getElementById('cookie-reject');
                const consentKey = 'cloudi_cookie_consent';

                if (!banner || !acceptButton || !rejectButton) {
                    return;
                }

                const existingConsent = localStorage.getItem(consentKey);

                if (existingConsent === 'accepted' || existingConsent === 'rejected') {
                    banner.remove();
                    return;
                }

                banner.classList.remove('hidden');

                const setCookieConsent = (value) => {
                    const maxAge = 60 * 60 * 24 * 180;
                    document.cookie = `cloudi_cookie_consent=${value}; max-age=${maxAge}; path=/; SameSite=Lax`;
                    localStorage.setItem(consentKey, value);
                    banner.remove();
                };

                acceptButton.addEventListener('click', () => setCookieConsent('accepted'));
                rejectButton.addEventListener('click', () => setCookieConsent('rejected'));
            })();
        </script>
    @endif
    @if($toast)
        <script>
            (() => {
                const toast = document.getElementById('app-toast');
                const toastCard = toast?.querySelector('.app-toast');
                const closeButton = document.getElementById('app-toast-close');

                if (!toast || !toastCard) {
                    return;
                }

                const closeToast = () => {
                    toastCard.classList.add('is-leaving');
                    setTimeout(() => toast.remove(), 220);
                };

                closeButton?.addEventListener('click', closeToast);
                setTimeout(closeToast, 3600);
            })();
        </script>
    @endif
</body>
</html>

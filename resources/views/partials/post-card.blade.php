<article class="group overflow-hidden rounded-[1.75rem] border border-stone-200 bg-white shadow-soft transition hover:-translate-y-1 hover:shadow-xl">
    @if($post->image)
        <div class="overflow-hidden">
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="h-56 w-full object-cover transition duration-500 group-hover:scale-105">
        </div>
    @else
        <div class="flex h-56 w-full items-center justify-center bg-[linear-gradient(135deg,_#ffede3_0%,_#fff7f1_55%,_#f6f1ea_100%)] px-8 text-center">
            <span class="font-serif text-3xl font-bold leading-tight text-stone-700">
                {{ \Illuminate\Support\Str::limit($post->title, 50) }}
            </span>
        </div>
    @endif

    <div class="space-y-4 p-6">
        @if($post->category)
            <a href="{{ route('categories.show', $post->category) }}" class="inline-flex text-xs font-extrabold uppercase tracking-[0.2em] text-brand-orange transition hover:text-stone-900">
                {{ $post->category->name }}
            </a>
        @endif

        <h2 class="text-2xl font-bold leading-snug text-brand-ink">
            <a href="{{ route('posts.show', $post) }}" class="transition group-hover:text-brand-orange">{{ $post->title }}</a>
        </h2>

        <p class="text-sm leading-7 text-stone-600">{{ \Illuminate\Support\Str::limit($post->excerpt, 140) }}</p>

        <div class="flex flex-wrap gap-2">
            @foreach($post->tags as $tag)
                <a href="{{ route('tags.show', $tag) }}" class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-500 transition hover:bg-brand-coral hover:text-brand-orange">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>

        <div class="flex items-center justify-between border-t border-stone-200 pt-4 text-xs font-semibold uppercase tracking-[0.16em] text-stone-400">
            <span>{{ $post->published_at->translatedFormat('d M Y') }}</span>
            <a href="{{ route('posts.show', $post) }}" class="text-brand-orange transition hover:text-stone-900">Leer más</a>
        </div>
    </div>
</article>

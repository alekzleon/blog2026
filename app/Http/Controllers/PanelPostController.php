<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PanelPostController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->with('category')
            ->latest('published_at')
            ->latest('id')
            ->paginate(15);

        return view('panel.posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load(['category', 'tags']);

        return view('panel.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $post->load(['category', 'tags']);

        return view('panel.posts.edit', [
            'post' => $post,
            'categoryName' => optional($post->category)->name,
            'tagsValue' => $post->tags->pluck('name')->implode(', '),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $category = null;

        if (! empty($validated['category_name'])) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($validated['category_name'])],
                ['name' => $validated['category_name']]
            );
        }

        if ($request->boolean('remove_image') && $post->image) {
            Storage::disk('public')->delete($post->image);
            $post->image = null;
        }

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $post->image = $request->file('image')->store('posts', 'public');
        }

        $post->fill([
            'category_id' => $category?->id,
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title'], $post->id),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'published_at' => $validated['published_at'] ?? null,
        ]);

        $post->save();

        $post->tags()->sync($this->syncTags($validated['tags'] ?? ''));

        return redirect()
            ->route('panel.posts.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'El artículo se actualizó correctamente.',
            ]);
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->tags()->detach();
        $post->delete();

        return redirect()
            ->route('panel.posts.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'El artículo se eliminó correctamente.',
            ]);
    }

    protected function syncTags(string $tags): array
    {
        $names = collect(explode(',', $tags))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->unique()
            ->values();

        return $names->map(function ($name) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );

            return $tag->id;
        })->all();
    }

    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 2;

        while (Post::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

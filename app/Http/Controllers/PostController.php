<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $featuredPost = Post::published()
            ->with(['category', 'tags'])
            ->latest('published_at')
            ->first();

        $posts = Post::published()
            ->with(['category', 'tags'])
            ->when($featuredPost, fn ($query) => $query->whereKeyNot($featuredPost->id))
            ->latest('published_at')
            ->paginate(9);

        return view('posts.index', compact('featuredPost', 'posts'));
    }

    public function show(Request $request, Post $post)
    {
        abort_unless($post->published_at && $post->published_at <= now(), 404);

        $viewCookieName = 'post_viewed_' . $post->id;

        if (! $request->cookie($viewCookieName)) {
            $post->increment('views_count');
            cookie()->queue(cookie($viewCookieName, '1', 60 * 24 * 7));
        }

        $post->load(['category', 'tags']);
        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();
        return view('posts.show', compact('post', 'related'));
    }
}

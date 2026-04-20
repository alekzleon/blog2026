<?php

namespace App\Http\Controllers;

use App\Services\AiPostPublisher;
use Illuminate\Http\Request;

class AiPostController extends Controller
{
    public function create()
    {
        return view('panel.ai-posts.create');
    }

    public function store(Request $request, AiPostPublisher $publisher)
    {
        $data = $request->validate([
            'topic' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
        ], [
            'topic.required' => 'Escribe el tema o título que quieres convertir en artículo.',
        ]);

        $post = $publisher->publish($data['topic'], $request->file('image'));

        return redirect()
            ->route('posts.show', $post)
            ->with('status', 'Artículo generado y publicado correctamente desde el panel IA.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Services\AiPostGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class AiPostController extends Controller
{
    public function create()
    {
        return view('panel.ai-posts.create');
    }

    public function store(Request $request, AiPostGenerator $generator)
    {
        $data = $request->validate([
            'topic' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
        ], [
            'topic.required' => 'Escribe el tema o título que quieres convertir en artículo.',
        ]);

        $generated = $generator->generate($data['topic']);

        $categoryName = Arr::get($generated, 'category') ?: 'General';
        $category = Category::firstOrCreate(
            ['slug' => Str::slug($categoryName)],
            ['name' => $categoryName, 'description' => 'Categoría generada desde el panel IA.']
        );

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        } else {
            $imagePrompt = Arr::get($generated, 'image_prompt');

            try {
                if ($imagePrompt) {
                    $imagePath = $generator->generateImage($imagePrompt, $data['topic']);
                }
            } catch (Throwable) {
                $imagePath = null;
            }

            if (! $imagePath) {
                $imagePath = $this->storeDefaultImage($data['topic']);
            }
        }

        $post = Post::create([
            'category_id' => $category->id,
            'title' => Arr::get($generated, 'title') ?: $data['topic'],
            'excerpt' => Arr::get($generated, 'excerpt'),
            'content' => Arr::get($generated, 'html') ?: '<p>Contenido no disponible.</p>',
            'image' => $imagePath,
            'published_at' => now(),
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('status', 'Artículo generado y publicado correctamente desde el panel IA.');
    }

    protected function storeDefaultImage(string $topic): string
    {
        $safeTopic = e(Str::limit($topic, 60));
        $fileName = 'posts/default-' . now()->format('YmdHis') . '-' . Str::random(8) . '.svg';

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1600" height="900" viewBox="0 0 1600 900" fill="none">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1600" y2="900" gradientUnits="userSpaceOnUse">
      <stop stop-color="#FFF4EB"/>
      <stop offset="0.55" stop-color="#F5EDE3"/>
      <stop offset="1" stop-color="#F0E3D3"/>
    </linearGradient>
  </defs>
  <rect width="1600" height="900" rx="44" fill="url(#bg)"/>
  <circle cx="1310" cy="160" r="190" fill="#FF6A1A" fill-opacity="0.10"/>
  <circle cx="250" cy="760" r="220" fill="#221F1A" fill-opacity="0.06"/>
  <rect x="118" y="118" width="1364" height="664" rx="34" fill="#FFFFFF" fill-opacity="0.68"/>
  <text x="160" y="270" fill="#FF6A1A" font-family="Arial, Helvetica, sans-serif" font-size="28" font-weight="700" letter-spacing="6">MI BLOG</text>
  <text x="160" y="380" fill="#221F1A" font-family="Georgia, serif" font-size="72" font-weight="700">{$safeTopic}</text>
  <text x="160" y="455" fill="#5F5A52" font-family="Arial, Helvetica, sans-serif" font-size="28">Imagen por defecto generada para el artículo</text>
  <rect x="160" y="520" width="230" height="56" rx="28" fill="#FF6A1A"/>
  <text x="206" y="556" fill="#FFFFFF" font-family="Arial, Helvetica, sans-serif" font-size="24" font-weight="700">Nuevo post</text>
</svg>
SVG;

        Storage::disk('public')->put($fileName, $svg);

        return $fileName;
    }
}

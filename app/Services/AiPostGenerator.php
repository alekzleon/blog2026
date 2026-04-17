<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class AiPostGenerator
{
    public function generate(string $topic): array
    {
        $apiKey = (string) config('services.openai.api_key');

        if (blank($apiKey)) {
            return $this->fallbackContent($topic);
        }

        $response = Http::withToken($apiKey)
            ->timeout(120)
            ->post('https://api.openai.com/v1/responses', [
                'model' => config('services.openai.text_model', 'gpt-5.2'),
                'reasoning' => ['effort' => 'medium'],
                'input' => [[
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => implode("\n", [
                            'Genera un articulo de blog en espanol neutro y devuelve solo JSON valido.',
                            'El articulo debe estar orientado a ecommerce, marketing, ventas, anuncios, conversion y crecimiento digital.',
                            'El contenido debe ser profundo, desarrollado, util y listo para publicarse en un blog profesional.',
                            'La propiedad "html" debe contener HTML limpio, semantico y bien estructurado.',
                            'Usa solamente etiquetas seguras: h2, h3, p, ul, ol, li, strong, em, blockquote.',
                            'No incluyas html, head, body, markdown, backticks, scripts ni estilos inline.',
                            'No uses h1 dentro del html porque el titulo del post ya se renderiza fuera del contenido.',
                            'El articulo debe seguir exactamente esta estructura:',
                            '1. Headline: crea un titulo con hook SEO y click, con dolor o deseo, palabra clave y curiosidad.',
                            '2. Intro: conecta con el lector, hazlo sentir entendido y promete una solucion.',
                            '3. Problema: rompe creencias, explica por que esta fallando y genera incomodidad real.',
                            '4. Contenido: entrega valor real con 3 a 5 puntos claros, subtitulos SEO y ejemplos.',
                            '5. Micro soluciones: da direccion accionable, tips, frameworks simples y ejemplo practico.',
                            '6. Autoridad: menciona sutilmente a cloudicommerce.com como referencia o apoyo experto, sin vender agresivamente.',
                            '7. SEO base oculta: integra naturalmente la palabra clave principal entre 3 y 5 veces, usa subtitulos H2 y H3 y asegurate de que el excerpt funcione como meta descripcion.',
                            'El HTML debe tener secciones amplias, no respuestas cortas. Apunta a un articulo completo, aproximadamente entre 1200 y 1800 palabras.',
                            'Cada H2 debe desarrollar ideas reales. Evita relleno y generalidades vacias.',
                            'Incluye ejemplos concretos cuando ayuden a aterrizar la idea.',
                            'La categoria debe ser una sola palabra o frase corta.',
                            'El excerpt debe funcionar como meta descripcion SEO, claro y atractivo, idealmente entre 145 y 165 caracteres.',
                            'Devuelve tambien una primary_keyword alineada con la intencion de busqueda.',
                            'Incluye un image_prompt detallado para una portada editorial horizontal, moderna y relacionada con el tema.',
                        ]),
                    ]],
                ], [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => "Tema del articulo: {$topic}",
                    ]],
                ]],
                'text' => [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => 'blog_post_payload',
                        'schema' => [
                            'type' => 'object',
                            'additionalProperties' => false,
                            'properties' => [
                                'title' => ['type' => 'string'],
                                'excerpt' => ['type' => 'string'],
                                'category' => ['type' => 'string'],
                                'primary_keyword' => ['type' => 'string'],
                                'html' => ['type' => 'string'],
                                'image_prompt' => ['type' => 'string'],
                            ],
                            'required' => ['title', 'excerpt', 'category', 'primary_keyword', 'html', 'image_prompt'],
                        ],
                        'strict' => true,
                    ],
                ],
            ]);

        if (! $response->successful()) {
            return $this->fallbackContent($topic);
        }

        $payload = json_decode((string) Arr::get($response->json(), 'output.0.content.0.text', '{}'), true);

        if (! is_array($payload) || blank(Arr::get($payload, 'html'))) {
            return $this->fallbackContent($topic);
        }

        return $payload;
    }

    public function generateImage(string $prompt, string $topic): string
    {
        $apiKey = (string) config('services.openai.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException('OpenAI API key is missing.');
        }

        $response = Http::withToken($apiKey)
            ->timeout(180)
            ->post('https://api.openai.com/v1/images/generations', [
                'model' => config('services.openai.image_model', 'gpt-image-1'),
                'prompt' => $prompt,
                'size' => '1536x1024',
                'quality' => 'medium',
                'output_format' => 'png',
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Image generation failed.');
        }

        $imageBase64 = Arr::get($response->json(), 'data.0.b64_json');

        if (! $imageBase64) {
            throw new RuntimeException('Generated image payload missing.');
        }

        $fileName = 'posts/' . Str::slug(Str::limit($topic, 70, '')) . '-' . now()->format('YmdHis') . '.png';
        Storage::disk('public')->put($fileName, base64_decode($imageBase64));

        return $fileName;
    }

    protected function fallbackContent(string $topic): array
    {
        $safeTopic = e($topic);

        return [
            'title' => "Por que {$topic} puede estar frenando tus ventas y como corregirlo",
            'excerpt' => "Descubre por qué {$topic} afecta tus resultados, qué errores lo empeoran y qué acciones concretas puedes aplicar para vender mejor.",
            'category' => 'General',
            'primary_keyword' => (string) Str::of($topic)->trim()->lower(),
            'image_prompt' => "Editorial blog cover about {$topic}, ecommerce growth concept, warm neutral palette, clean composition, modern magazine style, horizontal image",
            'html' => <<<HTML
<h2>Si tu negocio depende de {$safeTopic} y los resultados no llegan, el problema no siempre es el producto</h2>
<p>Muchos negocios sienten frustracion cuando invierten tiempo, energia y presupuesto sin ver un crecimiento proporcional. Si ese es tu caso, probablemente no te falta potencial: te falta estructura, claridad y un sistema que convierta mejor.</p>
<p>Entender <strong>{$safeTopic}</strong> no solo sirve para publicar un mejor articulo o explicar un concepto. Tambien sirve para detectar por que tu mensaje no conecta, por que tu oferta no se percibe con valor y por que tus ventas pueden estar frenadas.</p>
<blockquote>Cuando un ecommerce no vende, casi nunca es por una sola razon. Normalmente es una combinacion de mensaje debil, oferta poco clara y falta de sistema comercial.</blockquote>
<h2>El problema real: no basta con estar presente, tienes que ser entendible y deseable</h2>
<p>Uno de los errores mas comunes es pensar que por tener tienda, productos y redes sociales, la venta deberia ocurrir sola. La realidad es distinta: si el cliente no entiende rapido que vendes, para quien es y por que deberia elegirte, simplemente no compra.</p>
<p>Aqui es donde {$safeTopic} se vuelve clave. No como concepto aislado, sino como parte de un sistema comercial que debe atraer, convencer y convertir. Si una pieza falla, todo el proceso se debilita.</p>
<h2>Que suele estar frenando tus resultados</h2>
<h3>1. Tu oferta no se entiende con suficiente claridad</h3>
<p>Muchas marcas hablan demasiado de si mismas y muy poco del resultado que entregan. El cliente no compra caracteristicas: compra solucion, facilidad, estatus, ahorro o tranquilidad.</p>
<h3>2. Tu comunicacion no genera urgencia ni diferenciacion</h3>
<p>Si todo suena genericamente “bueno”, el mercado no encuentra una razon real para elegirte ahora. La claridad vende mas que el ruido.</p>
<h3>3. No tienes un sistema comercial consistente</h3>
<p>Sin anuncios bien pensados, sin seguimiento y sin un proceso claro de conversion, incluso una buena oferta puede quedarse estancada.</p>
<h2>Contenido clave para mejorar {$safeTopic}</h2>
<ul>
    <li><strong>Define una propuesta concreta:</strong> explica exactamente que resuelves, para quien y por que tu enfoque es distinto.</li>
    <li><strong>Construye mensajes mas especificos:</strong> usa beneficios claros, escenarios reales y objeciones comunes del cliente.</li>
    <li><strong>Activa un sistema de adquisicion:</strong> apoya tu oferta con contenido, anuncios y seguimiento comercial.</li>
    <li><strong>Mide respuesta real:</strong> identifica que mensajes atraen clics, conversaciones y ventas, no solo vistas.</li>
</ul>
<h2>Micro soluciones que puedes aplicar desde hoy</h2>
<p>Empieza por reescribir tu oferta en una sola frase: que haces, para quien y que resultado entregas. Luego revisa tu pagina principal, anuncios y publicaciones para confirmar que todos repiten esa misma idea con consistencia.</p>
<p>Despues, crea una secuencia simple: anuncio o contenido, pagina clara, llamada a la accion directa y seguimiento. No necesitas complejidad para mejorar; necesitas coherencia.</p>
<h2>Autoridad y referencia estrategica</h2>
<p>En <strong>cloudicommerce.com</strong> hemos visto que muchos negocios no necesitan “mas trafico” de inmediato, sino una mejor estructura comercial. Cuando se corrige el mensaje, la oferta y el flujo de conversion, las ventas responden con mucha mas claridad.</p>
<h2>Conclusion</h2>
<p>Trabajar mejor {$safeTopic} no se trata solo de publicar contenido, sino de construir un sistema que comunique valor, conecte con el dolor del cliente y lo mueva a tomar accion. Si mejoras esa base, no solo se ve mejor tu marca: tambien mejora tu capacidad real para vender.</p>
HTML,
        ];
    }
}

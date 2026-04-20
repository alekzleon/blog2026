<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateWhatsappPostJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsappBlogWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $secret = (string) config('services.whatsapp.blog_token');

        if (blank($secret)) {
            return response()->json([
                'ok' => false,
                'message' => 'Webhook token is not configured.',
            ], 500);
        }

        $payload = $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
            'text' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string', 'max:1000'],
            'topic' => ['nullable', 'string', 'max:255'],
            'token' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'from' => ['nullable', 'string', 'max:50'],
            'source' => ['nullable', 'string', 'max:50'],
        ]);

        $parsed = $this->extractCommand($payload);

        if (! $parsed) {
            return response()->json([
                'ok' => false,
                'message' => 'Formato no valido. Usa BLOG|TOKEN|TITULO.',
            ], 422);
        }

        if (! hash_equals($secret, $parsed['token'])) {
            Log::warning('WhatsApp blog webhook rejected due to invalid token.', [
                'phone' => $parsed['phone'],
                'source' => $parsed['source'],
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Token no valido.',
            ], 403);
        }

        $dedupeKey = 'whatsapp-blog:' . sha1(Str::lower($parsed['topic']) . '|' . ($parsed['phone'] ?? 'unknown'));
        if (! Cache::add($dedupeKey, true, now()->addMinutes(5))) {
            return response()->json([
                'ok' => true,
                'queued' => false,
                'message' => 'Ya recibimos una solicitud similar hace poco.',
                'topic' => $parsed['topic'],
            ]);
        }

        GenerateWhatsappPostJob::dispatchAfterResponse($parsed['topic'], $parsed['phone'], $parsed['source']);

        return response()->json([
            'ok' => true,
            'queued' => true,
            'message' => 'Solicitud recibida. El artículo se está generando.',
            'topic' => $parsed['topic'],
        ]);
    }

    protected function extractCommand(array $payload): ?array
    {
        $message = trim((string) ($payload['message'] ?? $payload['text'] ?? $payload['body'] ?? ''));
        $topic = trim((string) ($payload['topic'] ?? ''));
        $token = trim((string) ($payload['token'] ?? ''));
        $phone = trim((string) ($payload['phone'] ?? $payload['from'] ?? ''));
        $source = trim((string) ($payload['source'] ?? 'manychat'));

        if ($topic !== '' && $token !== '') {
            return [
                'topic' => $topic,
                'token' => $token,
                'phone' => $phone !== '' ? $phone : null,
                'source' => $source,
            ];
        }

        if ($message === '') {
            return null;
        }

        if (preg_match('/^\s*BLOG\s*\|\s*([A-Za-z0-9_-]+)\s*\|\s*(.+)\s*$/iu', $message, $matches)) {
            return [
                'topic' => trim($matches[2]),
                'token' => trim($matches[1]),
                'phone' => $phone !== '' ? $phone : null,
                'source' => $source,
            ];
        }

        if (preg_match('/^\s*(.+?)\s*\+\s*([A-Za-z0-9_-]+)\s*$/u', $message, $matches)) {
            return [
                'topic' => trim($matches[1]),
                'token' => trim($matches[2]),
                'phone' => $phone !== '' ? $phone : null,
                'source' => $source,
            ];
        }

        return null;
    }
}

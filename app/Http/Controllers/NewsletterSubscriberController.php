<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcomeMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class NewsletterSubscriberController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::query()
            ->latest('subscribed_at')
            ->latest('id')
            ->paginate(20);

        return view('panel.newsletter.index', compact('subscribers'));
    }

    public function export(): StreamedResponse
    {
        $fileName = 'newsletter-subscritos-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['Correo', 'Fecha de suscripcion']);

            NewsletterSubscriber::query()
                ->latest('subscribed_at')
                ->latest('id')
                ->chunk(500, function ($subscribers) use ($handle) {
                    foreach ($subscribers as $subscriber) {
                        fputcsv($handle, [
                            $subscriber->email,
                            optional($subscriber->subscribed_at)->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc,dns', 'max:255'],
        ], [
            'email.required' => 'Escribe tu correo para suscribirte.',
            'email.email' => 'Ingresa un correo válido.',
        ]);

        $subscriber = NewsletterSubscriber::firstOrNew([
            'email' => mb_strtolower(trim($validated['email'])),
        ]);

        if ($subscriber->exists) {
            return back()
                ->withInput()
                ->with('newsletter_status', 'Tu correo ya estaba suscrito al newsletter.')
                ->with('toast', [
                    'type' => 'info',
                    'message' => 'Tu correo ya estaba suscrito al newsletter.',
                ]);
        }

        $subscriber->subscribed_at = now();
        $subscriber->save();

        try {
            Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber->email));
        } catch (Throwable $exception) {
            report($exception);
        }

        return back()
            ->with('newsletter_status', 'Listo. Ya quedaste suscrito al newsletter.')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Listo. Ya quedaste suscrito al newsletter.',
            ]);
    }
}

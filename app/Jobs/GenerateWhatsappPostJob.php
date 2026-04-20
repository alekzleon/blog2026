<?php

namespace App\Jobs;

use App\Services\AiPostPublisher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateWhatsappPostJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $topic,
        public ?string $phone = null,
        public ?string $source = 'manychat',
    ) {
    }

    public function handle(AiPostPublisher $publisher): void
    {
        $post = $publisher->publish($this->topic);

        Log::info('WhatsApp blog post generated successfully.', [
            'topic' => $this->topic,
            'post_id' => $post->id,
            'post_slug' => $post->slug,
            'phone' => $this->phone,
            'source' => $this->source,
        ]);
    }
}

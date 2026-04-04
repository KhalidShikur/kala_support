<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $message;

    public function __construct(string $message = 'Hello from queue!')
    {
        $this->message = $message;
    }

    public function handle(): void
    {
        // safe operation that can’t fail
        Log::info('Queue executed successfully: ' . $this->message);
    }
}
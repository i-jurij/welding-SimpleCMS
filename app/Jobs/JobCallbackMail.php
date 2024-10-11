<?php

namespace App\Jobs;

use App\Mail\CallBackMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class JobCallbackMail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected array $data;
    // public $tries = 3;
    // public $backoff = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = date('Y-m-d_H:i:s');
        $data = $this->data;
        try {
            Mail::to('yjurij@gmail.com')->send(new CallBackMail($data));
        } catch (\Throwable $th) {
            $storageDestinationPath = storage_path('logs'.DIRECTORY_SEPARATOR.'callback_mail_error.log');
            if (!File::exists($storageDestinationPath)) {
                File::put($storageDestinationPath, "\n{$today}\ndd($th)\n");
            } else {
                File::append($storageDestinationPath, "\n{$today}\ndd($th)\n");
            }
        }
    }
}

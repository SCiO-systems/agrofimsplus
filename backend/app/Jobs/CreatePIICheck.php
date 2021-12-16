<?php

namespace App\Jobs;

use Log;
use Exception;
use App\Models\ResourceFile;
use Illuminate\Bus\Queueable;
use App\Utilities\SCIO\PIIChecker;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreatePIICheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The resource file.
     */
    protected $resourceFile;

    /**
     * The PII checker instance.
     */
    protected $piiChecker;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ResourceFile $resourceFile)
    {
        $this->resourceFile = $resourceFile;
        $this->piiChecker = new PIIChecker();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 1. Get the user of the resource file.
        // 2. Generate the pre-signed url.
        // 3. Send the details for the job.
        // 4. Get the response if it is successful and update the pii check id of the resource file.

        $user = $this->resourceFile->user;
        $path = $this->resourceFile->path;

        try {
            $response = $this->piiChecker->check($path, $user);
        } catch (Exception $ex) {
            Log::error('Failed to check file for PII information.', ['file' => $path, 'error' => $ex->getMessage()]);
        }

        $contents = $response[0];
        $id = $contents['jobID'];

        $this->resourceFile->setPIICheckIdentifier($id);

        Log::info('Got ID for PII check.', [
            'resourceFile' => $this->resourceFile->id,
            'checkId' => $id
        ]);
    }
}

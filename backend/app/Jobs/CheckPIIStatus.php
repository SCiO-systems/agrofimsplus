<?php

namespace App\Jobs;

use Log;
use App\Enums\PIIStatus;
use App\Models\ResourceFile;
use Illuminate\Bus\Queueable;
use App\Utilities\SCIO\PIIChecker;
use App\Utilities\SCIO\TokenGenerator;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CheckPIIStatus implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The files that should be checked.
     */
    protected $files;

    /**
     * The base URI of the service.
     */
    protected $baseURI;

    /**
     * The timeout of the http requests.
     */
    protected $requestTimeout;

    /**
     * The token to use for making requests to the service.
     */
    protected $token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $generator = new TokenGenerator();
        $this->token = $generator->getToken();
        $this->baseURI = env('SCIO_SERVICES_BASE_API_URL');
        $this->requestTimeout = env('REQUEST_TIMEOUT_SECONDS');
        $this->files = ResourceFile::where('pii_check_status', PIIStatus::PENDING)
            ->whereNotNull('pii_check_status_identifier')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->files)) {
            Log::info('No files to be checked for PII information.');
            return;
        }

        foreach ($this->files as $resourceFile) {

            $id = $resourceFile->pii_check_status_identifier;

            if (empty($id)) {
                Log::error('Failed to check status for file as the identifier is missing.', [
                    'resource_file' => $resourceFile->id,
                    'job' => get_class($this),
                ]);
                continue;
            }

            $checker = new PIIChecker();
            $status = $checker->getStatus($id);

            $resourceFile->setPIIStatus($status);

            Log::info('Got PII status for resource.', [
                'status' => $status,
                'id' => $id,
                'resourceFile' => $resourceFile->id,
            ]);
        }
    }
}

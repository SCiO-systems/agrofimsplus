<?php

namespace App\Console\Commands;

use App\Jobs\CreatePIICheck;
use App\Models\ResourceFile;
use Illuminate\Console\Command;

class PIIRunForAllFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pii:run-for-all-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggers a PII check to run for all files without a PII identifier.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $uncheckedFiles = ResourceFile::whereNull('pii_check_status_identifier')->get();

        foreach ($uncheckedFiles as $file) {
            CreatePIICheck::dispatch($file);
        }
    }
}

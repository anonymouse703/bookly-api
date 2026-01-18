<?php

namespace App\Console\Commands\Traits;

use Illuminate\Support\Facades\Log;

trait HasLogger
{
    protected function logStartedAt(): void
    {
        $started_at = now()->toDateTimeString();
        $process_name = $this->getName() ?? class_basename($this);
        $this->logInfo("{$process_name} started at {$started_at}");
    }

    protected function logEndedAt(): void
    {
        $started_at = now()->toDateTimeString();
        $process_name = $this->getName() ?? class_basename($this);
        $this->logInfo("{$process_name} ended at {$started_at}");
    }

    protected function logInfo($content): void
    {
        Log::channel($this->logChannel ?? 'daily')->info($content);
        $this->info($content);
    }

    protected function logError($content): void
    {
        Log::channel($this->logChannel ?? 'daily')->error($content);
        $this->error($content);
    }
}

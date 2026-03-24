<?php

namespace App\Livewire;

use App\Services\AuroraSyncClient;
use Livewire\Attributes\Polling;
use Livewire\Component;

class SyncProgress extends Component
{
    public ?array $job      = null;
    public array  $tables   = [];
    public ?array $twingate = null;
    public string $flash    = '';

    public function mount(): void
    {
        $this->refresh();
    }

    #[Polling(2000)]
    public function refresh(): void
    {
        $client = new AuroraSyncClient();
        $data = $client->getProgress();

        $this->job      = $data['job']      ?? null;
        $this->twingate = $data['twingate'] ?? null;

        // Keep only the latest log entry per table (highest id wins)
        $latest = [];
        foreach ($data['tables'] ?? [] as $row) {
            $name = $row['table_name'];
            if (!isset($latest[$name]) || $row['id'] > $latest[$name]['id']) {
                $latest[$name] = $row;
            }
        }
        $this->tables = array_values($latest);
    }

    public function start(): void
    {
        $client = new AuroraSyncClient();
        $result = $client->startJob();
        $this->flash = isset($result['error']) ? $result['error'] : 'Sync started.';
        $this->refresh();
    }

    public function pause(): void
    {
        if (! $this->job) return;
        $client = new AuroraSyncClient();
        $client->pauseJob($this->job['id']);
        $this->refresh();
    }

    public function resume(): void
    {
        if (! $this->job) return;
        $client = new AuroraSyncClient();
        $client->resumeJob($this->job['id']);
        $this->refresh();
    }

    public function cancel(): void
    {
        if (! $this->job) return;
        $client = new AuroraSyncClient();
        $client->cancelJob($this->job['id']);
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.sync-progress');
    }
}

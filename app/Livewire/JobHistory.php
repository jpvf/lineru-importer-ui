<?php

namespace App\Livewire;

use App\Services\AuroraSyncClient;
use Livewire\Component;

class JobHistory extends Component
{
    public array $jobs       = [];
    public ?int  $expandedId = null;
    public array $jobDetail  = [];

    public function mount(): void
    {
        $this->loadJobs();
    }

    public function loadJobs(): void
    {
        $client = new AuroraSyncClient();
        $data = $client->getJobs(30);
        $this->jobs = $data['jobs'] ?? [];
    }

    public function toggleExpand(int $jobId): void
    {
        if ($this->expandedId === $jobId) {
            $this->expandedId = null;
            $this->jobDetail  = [];
            return;
        }

        $client = new AuroraSyncClient();
        $data = $client->getCurrentJob();

        // Try specific job endpoint
        $result = (new AuroraSyncClient())->getJobs();
        $this->expandedId = $jobId;

        // Fetch job detail from /api/jobs/{id}
        $http = new \Illuminate\Http\Client\Factory();
        try {
            $r = \Illuminate\Support\Facades\Http::timeout(10)
                ->get(config('aurora.api_url') . "/api/jobs/{$jobId}");
            $this->jobDetail = $r->json()['tables'] ?? [];
        } catch (\Exception) {
            $this->jobDetail = [];
        }
    }

    public function render()
    {
        return view('livewire.job-history');
    }
}

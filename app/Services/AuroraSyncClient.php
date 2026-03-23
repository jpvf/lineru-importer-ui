<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class AuroraSyncClient
{
    private string $base;

    public function __construct()
    {
        $this->base = rtrim(config('aurora.api_url'), '/');
    }

    // ─── Tables ───────────────────────────────────────────────────────────────

    public function getTables(string $search = '', string $strategy = ''): array
    {
        return $this->get('/api/tables', array_filter([
            'search'   => $search,
            'strategy' => $strategy,
        ]));
    }

    public function triggerDiscovery(): array
    {
        return $this->post('/api/tables/discover');
    }

    public function setTableSelection(string $schema, string $table, bool $syncData): void
    {
        $this->patch("/api/tables/{$schema}/{$table}", ['sync_data' => $syncData]);
    }

    public function bulkSetSelection(array $selections): void
    {
        $this->post('/api/tables/selection', ['selections' => $selections]);
    }

    // ─── Jobs ─────────────────────────────────────────────────────────────────

    public function getJobs(int $limit = 20): array
    {
        return $this->get('/api/jobs', ['limit' => $limit]);
    }

    public function getCurrentJob(): array
    {
        return $this->get('/api/jobs/current');
    }

    public function startJob(): array
    {
        return $this->post('/api/jobs');
    }

    public function pauseJob(int $jobId): array
    {
        return $this->post("/api/jobs/{$jobId}/pause");
    }

    public function resumeJob(int $jobId): array
    {
        return $this->post("/api/jobs/{$jobId}/resume");
    }

    public function cancelJob(int $jobId): array
    {
        return $this->post("/api/jobs/{$jobId}/cancel");
    }

    // ─── Progress ─────────────────────────────────────────────────────────────

    public function getProgress(): array
    {
        return $this->get('/api/progress');
    }

    // ─── Settings ─────────────────────────────────────────────────────────────

    public function getSettings(): array
    {
        return $this->get('/api/settings');
    }

    public function updateSettings(array $data): array
    {
        return $this->put('/api/settings', $data);
    }

    public function testAurora(): array
    {
        return $this->post('/api/settings/test-aurora');
    }

    public function testLocal(): array
    {
        return $this->post('/api/settings/test-local');
    }

    public function testTelegram(): array
    {
        return $this->post('/api/settings/test-telegram');
    }

    public function getTwingate(): array
    {
        return $this->get('/api/settings/twingate');
    }

    // ─── HTTP helpers ─────────────────────────────────────────────────────────

    private function get(string $path, array $query = []): array
    {
        try {
            $r = Http::timeout(15)->get($this->base . $path, $query);
            return $r->json() ?? [];
        } catch (ConnectionException) {
            return ['error' => 'Cannot reach aurora-sync daemon'];
        }
    }

    private function post(string $path, array $body = []): array
    {
        try {
            $r = Http::timeout(15)->post($this->base . $path, $body);
            return $r->json() ?? [];
        } catch (ConnectionException) {
            return ['error' => 'Cannot reach aurora-sync daemon'];
        }
    }

    private function put(string $path, array $body = []): array
    {
        try {
            $r = Http::timeout(15)->put($this->base . $path, $body);
            return $r->json() ?? [];
        } catch (ConnectionException) {
            return ['error' => 'Cannot reach aurora-sync daemon'];
        }
    }

    private function patch(string $path, array $body = []): array
    {
        try {
            $r = Http::timeout(15)->patch($this->base . $path, $body);
            return $r->json() ?? [];
        } catch (ConnectionException) {
            return ['error' => 'Cannot reach aurora-sync daemon'];
        }
    }
}

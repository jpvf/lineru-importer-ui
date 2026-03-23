<?php

namespace App\Livewire;

use App\Services\AuroraSyncClient;
use Livewire\Attributes\Polling;
use Livewire\Component;

class TwingateStatus extends Component
{
    public ?array $status = null;

    public function mount(): void
    {
        $this->refresh();
    }

    #[Polling(30000)]
    public function refresh(): void
    {
        $this->status = (new AuroraSyncClient())->getTwingate();
    }

    public function render()
    {
        return view('livewire.twingate-badge');
    }
}

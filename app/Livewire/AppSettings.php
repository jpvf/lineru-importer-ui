<?php

namespace App\Livewire;

use App\Services\AuroraSyncClient;
use Livewire\Component;

class AppSettings extends Component
{
    public array  $form        = [];
    public string $flash       = '';
    public array  $testResults = [];

    public function mount(): void
    {
        $client = new AuroraSyncClient();
        $this->form = $client->getSettings();
    }

    public function save(): void
    {
        $client = new AuroraSyncClient();
        $client->updateSettings($this->form);
        $this->flash = 'Settings saved.';
    }

    public function testAurora(): void
    {
        $result = (new AuroraSyncClient())->testAurora();
        $this->testResults['aurora'] = $result;
    }

    public function testLocal(): void
    {
        $result = (new AuroraSyncClient())->testLocal();
        $this->testResults['local'] = $result;
    }

    public function testTelegram(): void
    {
        $result = (new AuroraSyncClient())->testTelegram();
        $this->testResults['telegram'] = $result;
    }

    public function render()
    {
        return view('livewire.app-settings');
    }
}

<?php

namespace App\Livewire;

use App\Services\AuroraSyncClient;
use Livewire\Component;

class TablesSelector extends Component
{
    public array  $tables        = [];
    public string $search        = '';
    public string $filterStrategy = '';
    public bool   $onlySelected  = false;
    public bool   $discovering   = false;
    public string $flash         = '';

    public function mount(): void
    {
        $this->loadTables();
    }

    public function loadTables(): void
    {
        $client = new AuroraSyncClient();
        $data = $client->getTables($this->search, $this->filterStrategy);
        $this->tables = $data['tables'] ?? [];
    }

    public function updatedSearch(): void
    {
        $this->loadTables();
    }

    public function updatedFilterStrategy(): void
    {
        $this->loadTables();
    }

    public function toggleTable(string $schema, string $table, bool $value): void
    {
        $client = new AuroraSyncClient();
        $client->setTableSelection($schema, $table, $value);

        // Update local state optimistically
        foreach ($this->tables as &$t) {
            if ($t['schema_name'] === $schema && $t['table_name'] === $table) {
                $t['sync_data'] = $value ? 1 : 0;
                break;
            }
        }
    }

    public function selectAll(): void
    {
        $client = new AuroraSyncClient();
        $selections = array_map(fn($t) => [
            'schema'    => $t['schema_name'],
            'table'     => $t['table_name'],
            'sync_data' => true,
        ], $this->filteredTables());

        $client->bulkSetSelection($selections);
        $this->loadTables();
    }

    public function deselectAll(): void
    {
        $client = new AuroraSyncClient();
        $selections = array_map(fn($t) => [
            'schema'    => $t['schema_name'],
            'table'     => $t['table_name'],
            'sync_data' => false,
        ], $this->filteredTables());

        $client->bulkSetSelection($selections);
        $this->loadTables();
    }

    public function discover(): void
    {
        $this->discovering = true;
        $client = new AuroraSyncClient();
        $client->triggerDiscovery();
        $this->flash = 'Discovery started — refresh in a few seconds.';
        $this->discovering = false;
    }

    public function filteredTables(): array
    {
        $tables = $this->tables;
        if ($this->onlySelected) {
            $tables = array_filter($tables, fn($t) => $t['sync_data']);
        }
        return array_values($tables);
    }

    public function selectedBytes(): int
    {
        return array_sum(array_column(
            array_filter($this->tables, fn($t) => $t['sync_data']),
            'data_length_bytes'
        ));
    }

    public function totalBytes(): int
    {
        return array_sum(array_column($this->tables, 'data_length_bytes'));
    }

    public function render()
    {
        return view('livewire.tables-selector', [
            'filteredTables' => $this->filteredTables(),
            'selectedBytes'  => $this->selectedBytes(),
            'totalBytes'     => $this->totalBytes(),
        ]);
    }
}

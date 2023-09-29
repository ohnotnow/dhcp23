<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DhcpEntry;

class DhcpEntries extends Component
{
    public $search = '';
    public $sortField = 'updated_at';
    public $sortDirection = 'desc';

    public function render()
    {
        return view('livewire.dhcp-entries', [
            'dhcpEntries' => $this->getDhcpEntries(),
        ]);
    }

    private function getDhcpEntries()
    {
        $searchTerm = trim(strtolower($this->search));
        return DhcpEntry::orderBy($this->sortField, $this->sortDirection)
            ->when(strlen($searchTerm) > 2, fn ($query) => $query->where(
                fn ($query) => $query->where('mac_address', 'like', "%{$this->search}%")
                    ->orWhere('ip_address', 'like', "%{$this->search}%")
                    ->orWhere('hostname', 'like', "%{$this->search}%")
                    ->orWhere('added_by', 'like', "%{$this->search}%")
                    ->orWhere('owner', 'like', "%{$this->search}%")
            ))
            ->get();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }
}

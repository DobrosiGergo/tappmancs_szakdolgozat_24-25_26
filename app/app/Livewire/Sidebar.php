<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public $isExpanded = true;

    public function mount()
    {
        $styleClass = $this->isExpanded ? 'ml-64' : 'ml-16';
        $this->dispatch('sidebarToggled', styleClass: $styleClass);
    }

    public function toggleSidebar()
    {
        $this->isExpanded = ! $this->isExpanded;

        $styleClass = $this->isExpanded ? 'ml-64' : 'ml-16';
        $this->dispatch('sidebarToggled', $styleClass);
    }

    public function render()
    {
        return view('livewire.sidebar');
    }
}

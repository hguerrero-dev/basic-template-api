<?php

namespace App\Livewire;

use Livewire\Component;

class Notification extends Component
{
    public $message = 'No tienes nuevas notificaciones.';

    public function openNotification()
    {
        $this->dispatch('open-modal', 'notification-modal');
    }

    public function render()
    {
        return view('components.ui.notification');
    }
}

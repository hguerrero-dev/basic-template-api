<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ConfirmModal extends Component
{
    public bool $show = false;

    public string $title = 'Confirmar acción';
    public string $message = '¿Estás seguro de continuar?';

    public string $actionEvent = '';
    public $params = null;

    #[On('open-confirm-modal')]
    public function openModal(array $payload = [])
    {
        $data = is_array($payload) && isset($payload[0]) ? $payload[0] : $payload;

        $this->title = $data['title'] ?? 'Confirmar acción';
        $this->message = $data['message'] ?? '¿Estás seguro de continuar?';
        $this->actionEvent = $data['event'] ?? 'confirm-action-default';
        $this->params = $data['params'] ?? null;

        $this->show = true;
    }

    public function confirm()
    {
        if ($this->params !== null) {
            $this->dispatch($this->actionEvent, $this->params);
        } else {
            $this->dispatch($this->actionEvent);
        }

        $this->show = false;
    }

    public function render()
    {
        return view('components.ui.confirm-modal');
    }
}

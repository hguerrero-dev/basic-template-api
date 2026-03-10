<?php

namespace App\Modules\Audit\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
// Asegúrate de importar tu modelo o servicio de Auditoría
use OwenIt\Auditing\Models\Audit; // <-- Asumiendo que usas owen-it/laravel-auditing, ajusta si tu modelo es otro

class AuditDetails extends Component
{
    public $audit = null;
    public bool $show = false;

    // Escucha el evento que lanzaste desde AuditList.php
    #[On('open-audit-details')]
    public function loadDetails($auditId)
    {
        // 1. Buscamos el registro en la base de datos
        $this->audit = Audit::with('user')->find($auditId);

        // 2. Le decimos a la vista que muestre el modal global
        $this->show = true;
    }

    public function render()
    {
        return view('audit::audit-details'); // o 'livewire.audit-details' dependiendo de cómo registraste tus vistas
    }
}

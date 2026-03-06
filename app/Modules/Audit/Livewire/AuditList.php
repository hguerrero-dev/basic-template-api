<?php

namespace App\Modules\Audit\Livewire;

use App\Modules\Audit\Services\AuditService;
use Livewire\Component;
use Livewire\WithPagination;

class AuditList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public $search = '';

    public function render(AuditService $auditService)
    {
        $audits = $auditService->getAll($this->search, 10);

        return view('audit::audit-list', [
            'audits' => $audits
        ]);
    }
}

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
    public $event = '';
    public $dateFrom = '';
    public $dateTo = '';

    public function clearFilters()
    {
        $this->reset(['search', 'event', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingEvent()
    {
        $this->resetPage();
    }
    public function updatingDateFrom()
    {
        $this->resetPage();
    }
    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function render(AuditService $auditService)
    {

        $filters = [
            'search' => $this->search,
            'event' => $this->event,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo
        ];

        $audits = $auditService->getAll($filters, 10);

        return view('audit::audit-list', [
            'audits' => $audits
        ]);
    }
}

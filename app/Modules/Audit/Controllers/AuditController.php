<?php

namespace App\Modules\Audit\Controllers;

use App\Modules\Audit\Services\AuditService;
use App\Modules\Core\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditController extends BaseController
{
    public function __construct(protected AuditService $auditService) {}

    public function index(Request $request): JsonResponse
    {
        $audits = $this->auditService->getAll(
            $request->input('search'),
            $request->input('per_page')
        );

        return $this->successResponse($audits, 'Auditorías obtenidas correctamente');
    }

    public function show($id): JsonResponse
    {
        $audit = $this->auditService->getByOne($id);

        return $this->successResponse($audit, 'Auditoría obtenida correctamente');
    }
}

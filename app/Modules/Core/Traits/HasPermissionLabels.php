<?php

namespace App\Modules\Core\Traits;

trait HasPermissionLabels
{
    public function label(): string
    {
        $action = $this->name;

        $actionLabel = match ($action) {
            'View'   => 'Ver',
            'Create' => 'Crear',
            'Edit'   => 'Editar',
            'Delete' => 'Eliminar',
            'Restore' => 'Restaurar',
            'ForceDelete' => 'Eliminar permanentemente',
            default  => $action,
        };

        $subjectLabel = $this->getModuleLabel();

        return "$actionLabel $subjectLabel";
    }

    public function getModuleLabel(): string
    {
        $baseName = str_replace('Permission', '', class_basename($this));

        return $baseName;
    }
}

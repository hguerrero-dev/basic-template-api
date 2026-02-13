<?php

namespace App\Modules\Roles\Requests;

use App\Modules\Core\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role')?->id ?? $this->route('role');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->where(function ($query) {
                    return $query->where('guard_name', 'api');
                })->ignore($roleId)
            ],
            // => Validate that 'permissions' is an array of existing permission IDs
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.string' => 'El nombre del rol debe ser una cadena de texto.',
            'name.max' => 'El nombre del rol no debe exceder los 255 caracteres.',
            'name.unique' => 'Ya existe un rol con ese nombre.',

            'permissions.array' => 'Los permisos deben ser un arreglo.',
            'permissions.*.integer' => 'Cada permiso debe ser un ID entero.',
            'permissions.*.exists' => 'Uno o más permisos no son válidos.',
        ];
    }
}

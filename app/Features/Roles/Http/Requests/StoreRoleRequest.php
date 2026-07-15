<?php

declare(strict_types=1);

namespace App\Features\Roles\Http\Requests;

use App\Core\Authorization\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::RolesManage->value) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:roles,name'],
            'label' => ['required', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => [Rule::in(Permission::values())],
        ];
    }
}

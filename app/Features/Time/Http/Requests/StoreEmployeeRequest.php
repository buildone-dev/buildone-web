<?php

declare(strict_types=1);

namespace App\Features\Time\Http\Requests;

use App\Core\Authorization\Permission;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::TimeManage->value) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:employees,code'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}

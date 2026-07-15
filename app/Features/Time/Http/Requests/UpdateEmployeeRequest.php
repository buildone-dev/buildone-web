<?php

declare(strict_types=1);

namespace App\Features\Time\Http\Requests;

use App\Core\Authorization\Permission;
use App\Features\Time\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
        /** @var Employee $employee */
        $employee = $this->route('employee');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('employees', 'code')->ignore($employee->id)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}

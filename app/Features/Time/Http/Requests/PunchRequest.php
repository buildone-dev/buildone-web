<?php

declare(strict_types=1);

namespace App\Features\Time\Http\Requests;

use App\Core\Authorization\Permission;
use Illuminate\Foundation\Http\FormRequest;

class PunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::TimeView->value) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255'],
        ];
    }
}

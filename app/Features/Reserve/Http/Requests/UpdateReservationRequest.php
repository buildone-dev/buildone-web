<?php

declare(strict_types=1);

namespace App\Features\Reserve\Http\Requests;

use App\Core\Authorization\Permission;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::ReserveManage->value) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'reserved_at' => ['required', 'date'],
            'party_size' => ['required', 'integer', 'min:1', 'max:999'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Features\Entry\Http\Requests;

use App\Core\Authorization\Permission;
use App\Features\Entry\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::EntryManage->value) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Member $member */
        $member = $this->route('member');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('members', 'code')->ignore($member->id)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}

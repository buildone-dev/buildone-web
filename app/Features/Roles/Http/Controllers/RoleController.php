<?php

declare(strict_types=1);

namespace App\Features\Roles\Http\Controllers;

use App\Core\Audit\AuditLogger;
use App\Core\Authorization\Models\Role;
use App\Core\Authorization\Permission;
use App\Features\Roles\Http\Requests\StoreRoleRequest;
use App\Features\Roles\Http\Requests\UpdateRoleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class RoleController extends Controller
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function index(): View
    {
        Gate::authorize(Permission::RolesManage->value);

        return view('roles::index', [
            'roles' => Role::query()->withCount('users')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize(Permission::RolesManage->value);

        return view('roles::form', ['role' => new Role, 'permissions' => Permission::cases()]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['permissions'] = $data['permissions'] ?? [];

        $role = Role::query()->create($data);
        $this->audit->log('role.created', $role, ['name' => $role->name]);

        return redirect()->route('roles.index')->with('status', 'ロールを作成しました。');
    }

    public function edit(Role $role): View
    {
        Gate::authorize(Permission::RolesManage->value);

        return view('roles::form', ['role' => $role, 'permissions' => Permission::cases()]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $data = $request->validated();

        // super_admin は常に全権限（権限の変更は受け付けず、ラベルのみ更新）
        $role->label = (string) $data['label'];
        if ($role->name !== Role::SUPER_ADMIN) {
            $role->permissions = $data['permissions'] ?? [];
        }
        $role->save();

        $this->audit->log('role.updated', $role, ['name' => $role->name]);

        return redirect()->route('roles.index')->with('status', 'ロールを更新しました。');
    }

    public function destroy(Role $role): RedirectResponse
    {
        Gate::authorize(Permission::RolesManage->value);

        if ($role->name === Role::SUPER_ADMIN) {
            return redirect()->route('roles.index')->with('role_error', 'スーパー管理者ロールは削除できません。');
        }

        if ($role->users()->exists()) {
            return redirect()->route('roles.index')->with('role_error', 'このロールが割り当てられたユーザーがいるため削除できません。');
        }

        $this->audit->log('role.deleted', $role, ['name' => $role->name]);
        $role->delete();

        return redirect()->route('roles.index')->with('status', 'ロールを削除しました。');
    }
}

<?php

declare(strict_types=1);

namespace App\Features\Users\Http\Controllers;

use App\Core\Audit\AuditLogger;
use App\Core\Authorization\Models\Role;
use App\Core\Authorization\Permission;
use App\Features\Users\Http\Requests\StoreUserRequest;
use App\Features\Users\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

final class UserController extends Controller
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function index(): View
    {
        Gate::authorize(Permission::UsersView->value);

        return view('users::index', [
            'users' => User::query()->with('role')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        Gate::authorize(Permission::UsersManage->value);

        return view('users::form', [
            'user' => new User,
            'roles' => Role::query()->orderBy('label')->get(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::query()->create($data);
        $this->audit->log('user.created', $user, ['email' => $user->email]);

        return redirect()->route('users.index')->with('status', 'ユーザーを作成しました。');
    }

    public function edit(User $user): View
    {
        Gate::authorize(Permission::UsersManage->value);

        return view('users::form', [
            'user' => $user,
            'roles' => Role::query()->orderBy('label')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        $this->audit->log('user.updated', $user, ['email' => $user->email]);

        return redirect()->route('users.index')->with('status', 'ユーザーを更新しました。');
    }

    public function toggle(User $user): RedirectResponse
    {
        Gate::authorize(Permission::UsersManage->value);

        $user->update(['is_active' => ! $user->is_active]);
        $this->audit->log($user->is_active ? 'user.enabled' : 'user.disabled', $user);

        return redirect()->route('users.index')->with('status', '状態を変更しました。');
    }
}

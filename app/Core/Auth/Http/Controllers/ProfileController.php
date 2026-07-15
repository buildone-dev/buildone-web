<?php

declare(strict_types=1);

namespace App\Core\Auth\Http\Controllers;

use App\Core\Audit\AuditLogger;
use App\Core\Auth\Http\Requests\UpdatePasswordRequest;
use App\Core\Auth\Http\Requests\UpdateProfileRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

final class ProfileController extends Controller
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function edit(): View
    {
        return view('auth::profile');
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->update($request->validated());

        $this->audit->log('profile.updated', $user);

        return redirect()->route('profile.edit')->with('status', 'プロフィールを更新しました。');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->update(['password' => Hash::make((string) $request->validated()['password'])]);

        $this->audit->log('profile.password_changed', $user);

        return redirect()->route('profile.edit')->with('status', 'パスワードを変更しました。');
    }
}

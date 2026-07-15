<?php

declare(strict_types=1);

namespace App\Core\Dashboard\Http\Controllers;

use App\Core\Audit\Models\AuditLog;
use App\Core\Authorization\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;

final class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard::index', [
            'userCount' => User::query()->count(),
            'activeCount' => User::query()->where('is_active', true)->count(),
            'roleCount' => Role::query()->count(),
            'auditCount' => AuditLog::query()->count(),
            'recent' => AuditLog::query()->with('actor')->latest('occurred_at')->limit(8)->get(),
        ]);
    }
}

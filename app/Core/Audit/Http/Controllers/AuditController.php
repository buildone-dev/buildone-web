<?php

declare(strict_types=1);

namespace App\Core\Audit\Http\Controllers;

use App\Core\Audit\Models\AuditLog;
use App\Core\Authorization\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

final class AuditController extends Controller
{
    public function index(): View
    {
        Gate::authorize(Permission::AuditView->value);

        return view('audit::index', [
            'logs' => AuditLog::query()->with('actor')->latest('occurred_at')->paginate(20),
        ]);
    }
}

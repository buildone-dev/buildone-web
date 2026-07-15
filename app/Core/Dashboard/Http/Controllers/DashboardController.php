<?php

declare(strict_types=1);

namespace App\Core\Dashboard\Http\Controllers;

use App\Core\Audit\Models\AuditLog;
use App\Core\Authorization\Models\Role;
use App\Core\Dashboard\WidgetRegistry;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;

final class DashboardController extends Controller
{
    public function index(WidgetRegistry $widgets): View
    {
        $businessWidgets = array_map(
            static fn ($widget): array => [
                'label' => $widget->label,
                'value' => $widget->resolve(),
                'url' => $widget->url(),
            ],
            $widgets->visible(),
        );

        return view('dashboard::index', [
            'widgets' => $businessWidgets,
            'userCount' => User::query()->count(),
            'activeCount' => User::query()->where('is_active', true)->count(),
            'roleCount' => Role::query()->count(),
            'auditCount' => AuditLog::query()->count(),
            'recent' => AuditLog::query()->with('actor')->latest('occurred_at')->limit(8)->get(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Features\Entry\Http\Controllers;

use App\Core\Authorization\Permission;
use App\Features\Entry\Models\AttendanceLog;
use App\Features\Entry\Models\Member;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class AttendanceController extends Controller
{
    public function index(): View
    {
        Gate::authorize(Permission::EntryView->value);

        return view('entry::index', [
            'todayIn' => AttendanceLog::query()->where('direction', AttendanceLog::IN)->whereDate('recorded_at', today())->count(),
            'presentCount' => Member::query()->where('present', true)->count(),
            'memberCount' => Member::query()->where('is_active', true)->count(),
            'logs' => AttendanceLog::query()->with('member')->latest('recorded_at')->paginate(20),
        ]);
    }

    public function export(): StreamedResponse
    {
        Gate::authorize(Permission::EntryView->value);

        $filename = 'attendance_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');

            if ($out === false) {
                return;
            }

            // Excel が UTF-8 を正しく開くよう BOM を付与
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['利用者', 'コード', '区分', '日時', '方法']);

            AttendanceLog::query()
                ->with('member')
                ->latest('recorded_at')
                ->chunk(500, function ($logs) use ($out): void {
                    foreach ($logs as $log) {
                        fputcsv($out, [
                            $log->member->name,
                            $log->member->code,
                            $log->isEntry() ? '入室' : '退室',
                            $log->recorded_at->format('Y-m-d H:i:s'),
                            $log->method,
                        ]);
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}

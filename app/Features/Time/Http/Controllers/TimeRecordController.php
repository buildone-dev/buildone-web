<?php

declare(strict_types=1);

namespace App\Features\Time\Http\Controllers;

use App\Core\Authorization\Permission;
use App\Features\Time\Models\Employee;
use App\Features\Time\Models\TimeRecord;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimeRecordController extends Controller
{
    public function index(): View
    {
        Gate::authorize(Permission::TimeView->value);

        $todayMinutes = (int) TimeRecord::query()
            ->whereDate('clock_in', today())
            ->sum('worked_minutes');

        return view('time::index', [
            'workingCount' => Employee::query()->where('working', true)->count(),
            'employeeCount' => Employee::query()->where('is_active', true)->count(),
            'todayHours' => intdiv($todayMinutes, 60).'時間'.($todayMinutes % 60).'分',
            'records' => TimeRecord::query()->with('employee')->latest('clock_in')->paginate(20),
        ]);
    }

    public function monthly(): View
    {
        Gate::authorize(Permission::TimeView->value);

        $month = (string) request('month', today()->format('Y-m'));
        if (preg_match('/^\d{4}-\d{2}$/', $month) !== 1) {
            $month = today()->format('Y-m');
        }

        $start = Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $aggregates = DB::table('time_records')
            ->whereBetween('clock_in', [$start, $end])
            ->whereNotNull('worked_minutes')
            ->selectRaw('employee_id, COUNT(*) as day_count, SUM(worked_minutes) as total_minutes')
            ->groupBy('employee_id')
            ->get();

        $employees = Employee::query()->whereIn('id', $aggregates->pluck('employee_id'))->pluck('name', 'id');

        $rows = $aggregates
            ->map(static fn ($row): array => [
                'name' => $employees[$row->employee_id] ?? '—',
                'days' => (int) $row->day_count,
                'minutes' => (int) $row->total_minutes,
            ])
            ->sortByDesc('minutes')
            ->values()
            ->all();

        return view('time::monthly', [
            'month' => $month,
            'rows' => $rows,
            'totalMinutes' => array_sum(array_column($rows, 'minutes')),
        ]);
    }

    public function export(): StreamedResponse
    {
        Gate::authorize(Permission::TimeView->value);

        $filename = 'timesheet_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');

            if ($out === false) {
                return;
            }

            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['従業員', 'コード', '出勤', '退勤', '勤務時間(分)']);

            TimeRecord::query()
                ->with('employee')
                ->latest('clock_in')
                ->chunk(500, function ($records) use ($out): void {
                    foreach ($records as $record) {
                        fputcsv($out, [
                            $record->employee->name,
                            $record->employee->code,
                            $record->clock_in->format('Y-m-d H:i'),
                            $record->clock_out?->format('Y-m-d H:i') ?? '',
                            $record->worked_minutes ?? '',
                        ]);
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}

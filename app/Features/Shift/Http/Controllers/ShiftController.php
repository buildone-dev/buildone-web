<?php

declare(strict_types=1);

namespace App\Features\Shift\Http\Controllers;

use App\Core\Audit\AuditLogger;
use App\Core\Authorization\Permission;
use App\Features\Shift\Http\Requests\StoreShiftRequest;
use App\Features\Shift\Http\Requests\UpdateShiftRequest;
use App\Features\Shift\Models\Shift;
use App\Features\Time\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ShiftController extends Controller
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function index(): View
    {
        Gate::authorize(Permission::ShiftView->value);

        return view('shift::index', [
            'todayCount' => Shift::query()->whereDate('work_date', today())->count(),
            'weekCount' => Shift::query()->whereBetween('work_date', [today()->startOfWeek(), today()->endOfWeek()])->count(),
            'employeeCount' => Employee::query()->where('is_active', true)->count(),
            'shifts' => Shift::query()
                ->with('employee')
                ->where('work_date', '>=', today())
                ->orderBy('work_date')
                ->orderBy('start_time')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        Gate::authorize(Permission::ShiftManage->value);

        return view('shift::form', [
            'shift' => new Shift,
            'employees' => $this->activeEmployees(),
        ]);
    }

    public function store(StoreShiftRequest $request): RedirectResponse
    {
        $shift = Shift::query()->create($request->validated());
        $this->audit->log('shift.created', $shift);

        return redirect()->route('shift.index')->with('status', 'シフトを登録しました。');
    }

    public function edit(Shift $shift): View
    {
        Gate::authorize(Permission::ShiftManage->value);

        return view('shift::form', [
            'shift' => $shift,
            'employees' => $this->activeEmployees(),
        ]);
    }

    public function update(UpdateShiftRequest $request, Shift $shift): RedirectResponse
    {
        $shift->update($request->validated());
        $this->audit->log('shift.updated', $shift);

        return redirect()->route('shift.index')->with('status', 'シフトを更新しました。');
    }

    public function destroy(Shift $shift): RedirectResponse
    {
        Gate::authorize(Permission::ShiftManage->value);

        $this->audit->log('shift.deleted', $shift);
        $shift->delete();

        return redirect()->route('shift.index')->with('status', 'シフトを削除しました。');
    }

    public function export(): StreamedResponse
    {
        Gate::authorize(Permission::ShiftView->value);

        $filename = 'shifts_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');

            if ($out === false) {
                return;
            }

            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['日付', '従業員', '開始', '終了', 'メモ']);

            Shift::query()
                ->with('employee')
                ->orderBy('work_date')
                ->orderBy('start_time')
                ->chunk(500, function ($shifts) use ($out): void {
                    foreach ($shifts as $shift) {
                        fputcsv($out, [
                            $shift->work_date->format('Y-m-d'),
                            $shift->employee->name,
                            $shift->start_time,
                            $shift->end_time,
                            $shift->note ?? '',
                        ]);
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return Collection<int, Employee>
     */
    private function activeEmployees()
    {
        return Employee::query()->where('is_active', true)->orderBy('name')->get();
    }
}

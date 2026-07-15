<?php

declare(strict_types=1);

namespace App\Features\Time\Http\Controllers;

use App\Core\Audit\AuditLogger;
use App\Core\Authorization\Permission;
use App\Features\Time\Http\Requests\StoreEmployeeRequest;
use App\Features\Time\Http\Requests\UpdateEmployeeRequest;
use App\Features\Time\Models\Employee;
use App\Features\Time\Support\EmployeeCsvImporter;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class EmployeeController extends Controller
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function index(): View
    {
        Gate::authorize(Permission::TimeView->value);

        $q = trim((string) request('q'));

        return view('time::employees.index', [
            'employees' => Employee::query()
                ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('code', 'like', "%{$q}%")))
                ->orderBy('name')->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize(Permission::TimeManage->value);

        return view('time::employees.form', ['employee' => new Employee(['is_active' => true])]);
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $employee = Employee::query()->create($request->validated());
        $this->audit->log('employee.created', $employee, ['code' => $employee->code]);

        return redirect()->route('time.employees.index')->with('status', '従業員を登録しました。');
    }

    public function import(Request $request, EmployeeCsvImporter $importer): RedirectResponse
    {
        Gate::authorize(Permission::TimeManage->value);

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $result = $importer->import($request->file('file')->getRealPath());
        $this->audit->log('employee.imported', null, $result);

        return redirect()->route('time.employees.index')
            ->with('status', "{$result['created']}件を取り込みました（スキップ {$result['skipped']}件）。");
    }

    public function edit(Employee $employee): View
    {
        Gate::authorize(Permission::TimeManage->value);

        return view('time::employees.form', ['employee' => $employee]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());
        $this->audit->log('employee.updated', $employee, ['code' => $employee->code]);

        return redirect()->route('time.employees.index')->with('status', '従業員を更新しました。');
    }

    public function export(): StreamedResponse
    {
        Gate::authorize(Permission::TimeView->value);

        $filename = 'employees_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');

            if ($out === false) {
                return;
            }

            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['名前', 'コード', '状態']);

            Employee::query()->orderBy('name')->chunk(500, function ($employees) use ($out): void {
                foreach ($employees as $employee) {
                    fputcsv($out, [$employee->name, $employee->code, $employee->is_active ? '有効' : '無効']);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function toggle(Employee $employee): RedirectResponse
    {
        Gate::authorize(Permission::TimeManage->value);

        $employee->update(['is_active' => ! $employee->is_active]);
        $this->audit->log($employee->is_active ? 'employee.enabled' : 'employee.disabled', $employee);

        return redirect()->route('time.employees.index')->with('status', '状態を変更しました。');
    }
}

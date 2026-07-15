<?php

declare(strict_types=1);

namespace App\Features\Time\Http\Controllers;

use App\Core\Audit\AuditLogger;
use App\Core\Authorization\Permission;
use App\Features\Time\Http\Requests\StoreEmployeeRequest;
use App\Features\Time\Http\Requests\UpdateEmployeeRequest;
use App\Features\Time\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

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

    public function toggle(Employee $employee): RedirectResponse
    {
        Gate::authorize(Permission::TimeManage->value);

        $employee->update(['is_active' => ! $employee->is_active]);
        $this->audit->log($employee->is_active ? 'employee.enabled' : 'employee.disabled', $employee);

        return redirect()->route('time.employees.index')->with('status', '状態を変更しました。');
    }
}

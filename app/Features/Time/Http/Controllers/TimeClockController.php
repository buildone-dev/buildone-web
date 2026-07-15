<?php

declare(strict_types=1);

namespace App\Features\Time\Http\Controllers;

use App\Core\Authorization\Permission;
use App\Features\Time\Http\Requests\PunchRequest;
use App\Features\Time\Models\Employee;
use App\Features\Time\Models\TimeRecord;
use App\Features\Time\Services\TimeClockService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class TimeClockController extends Controller
{
    public function show(): View
    {
        Gate::authorize(Permission::TimeView->value);

        return view('time::kiosk', [
            'workingCount' => Employee::query()->where('working', true)->count(),
            'recent' => TimeRecord::query()->with('employee')->latest('id')->limit(8)->get(),
        ]);
    }

    public function punch(PunchRequest $request, TimeClockService $service): RedirectResponse
    {
        $code = (string) $request->validated()['code'];

        $employee = Employee::query()->where('code', $code)->where('is_active', true)->first();

        if ($employee === null) {
            return back()->with('punch_error', "コード「{$code}」の従業員が見つかりません。");
        }

        $result = $service->punch($employee);

        return back()
            ->with('punch_result', $employee->name.' さんが '.($result['direction'] === 'in' ? '出勤' : '退勤').' しました')
            ->with('punch_direction', $result['direction']);
    }
}

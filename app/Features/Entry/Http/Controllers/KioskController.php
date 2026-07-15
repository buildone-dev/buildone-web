<?php

declare(strict_types=1);

namespace App\Features\Entry\Http\Controllers;

use App\Core\Authorization\Permission;
use App\Features\Entry\Http\Requests\PunchRequest;
use App\Features\Entry\Models\AttendanceLog;
use App\Features\Entry\Models\Member;
use App\Features\Entry\Services\AttendanceService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class KioskController extends Controller
{
    public function show(): View
    {
        Gate::authorize(Permission::EntryView->value);

        return view('entry::kiosk', [
            'presentCount' => Member::query()->where('present', true)->count(),
            'recent' => AttendanceLog::query()->with('member')->latest('recorded_at')->limit(8)->get(),
        ]);
    }

    public function punch(PunchRequest $request, AttendanceService $service): RedirectResponse
    {
        $code = (string) $request->validated()['code'];

        $member = Member::query()->where('code', $code)->where('is_active', true)->first();

        if ($member === null) {
            return back()->with('punch_error', "コード「{$code}」は登録されていません。");
        }

        $log = $service->punch($member);

        return back()
            ->with('punch_result', $member->name.' さんが '.($log->isEntry() ? '入室' : '退室').' しました')
            ->with('punch_direction', $log->direction);
    }
}

<?php

declare(strict_types=1);

namespace App\Features\Entry\Http\Controllers;

use App\Core\Audit\AuditLogger;
use App\Core\Authorization\Permission;
use App\Features\Entry\Http\Requests\StoreMemberRequest;
use App\Features\Entry\Http\Requests\UpdateMemberRequest;
use App\Features\Entry\Models\Member;
use App\Features\Entry\Support\MemberCsvImporter;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class MemberController extends Controller
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function index(): View
    {
        Gate::authorize(Permission::EntryView->value);

        $q = trim((string) request('q'));

        return view('entry::members.index', [
            'members' => Member::query()
                ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('code', 'like', "%{$q}%")))
                ->orderBy('name')->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize(Permission::EntryManage->value);

        return view('entry::members.form', ['member' => new Member(['is_active' => true])]);
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $member = Member::query()->create($request->validated());
        $this->audit->log('member.created', $member, ['code' => $member->code]);

        return redirect()->route('entry.members.index')->with('status', '利用者を登録しました。');
    }

    public function edit(Member $member): View
    {
        Gate::authorize(Permission::EntryManage->value);

        return view('entry::members.form', ['member' => $member]);
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $member->update($request->validated());
        $this->audit->log('member.updated', $member, ['code' => $member->code]);

        return redirect()->route('entry.members.index')->with('status', '利用者を更新しました。');
    }

    public function import(Request $request, MemberCsvImporter $importer): RedirectResponse
    {
        Gate::authorize(Permission::EntryManage->value);

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $result = $importer->import($request->file('file')->getRealPath());
        $this->audit->log('member.imported', null, $result);

        return redirect()->route('entry.members.index')
            ->with('status', "{$result['created']}件を取り込みました（スキップ {$result['skipped']}件）。");
    }

    public function toggle(Member $member): RedirectResponse
    {
        Gate::authorize(Permission::EntryManage->value);

        $member->update(['is_active' => ! $member->is_active]);
        $this->audit->log($member->is_active ? 'member.enabled' : 'member.disabled', $member);

        return redirect()->route('entry.members.index')->with('status', '状態を変更しました。');
    }
}

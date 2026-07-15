<?php

declare(strict_types=1);

use App\Core\Audit\Models\AuditLog;
use App\Core\Authorization\Permission;
use App\Features\Entry\Models\AttendanceLog;
use App\Features\Entry\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('権限がないと打刻画面を開けない', function (): void {
    actingAs(makeUserWith([]))->get('/admin/entry/kiosk')->assertForbidden();
});

it('打刻画面を開ける（EntryView）', function (): void {
    actingAs(makeUserWith([Permission::EntryView->value]))
        ->get('/admin/entry/kiosk')
        ->assertOk()
        ->assertSee('打刻');
});

it('打刻すると入室→退室がトグルされ記録が残る', function (): void {
    $user = makeUserWith([Permission::EntryView->value]);
    $member = Member::factory()->create(['present' => false]);

    // 1回目：入室
    actingAs($user)->post('/admin/entry/punch', ['code' => $member->code])->assertRedirect();
    expect($member->fresh()->present)->toBeTrue();
    expect(AttendanceLog::query()->where('member_id', $member->id)->where('direction', 'in')->exists())->toBeTrue();

    // 2回目：退室
    actingAs($user)->post('/admin/entry/punch', ['code' => $member->code])->assertRedirect();
    expect($member->fresh()->present)->toBeFalse();
    expect(AttendanceLog::query()->where('member_id', $member->id)->where('direction', 'out')->exists())->toBeTrue();
});

it('未登録コードの打刻はエラーになり記録されない', function (): void {
    actingAs(makeUserWith([Permission::EntryView->value]))
        ->post('/admin/entry/punch', ['code' => 'UNKNOWN-CODE'])
        ->assertSessionHas('punch_error');

    expect(AttendanceLog::query()->count())->toBe(0);
});

it('入退室状況ページに集計が表示される', function (): void {
    Member::factory()->count(2)->create(['present' => true]);

    actingAs(makeUserWith([Permission::EntryView->value]))
        ->get('/admin/entry')
        ->assertOk()
        ->assertSee('現在の在室');
});

it('利用者を登録でき監査に残る', function (): void {
    actingAs(makeUserWith([Permission::EntryManage->value]))
        ->post('/admin/entry/members', ['name' => '田中 太郎', 'code' => 'CARD-0001'])
        ->assertRedirect('/admin/entry/members');

    expect(Member::query()->where('code', 'CARD-0001')->exists())->toBeTrue();
    expect(AuditLog::query()->where('action', 'member.created')->exists())->toBeTrue();
});

it('コードの重複は登録できない', function (): void {
    Member::factory()->create(['code' => 'CARD-0001']);

    actingAs(makeUserWith([Permission::EntryManage->value]))
        ->post('/admin/entry/members', ['name' => '別人', 'code' => 'CARD-0001'])
        ->assertSessionHasErrors('code');
});

it('閲覧権限では利用者登録画面を開けない', function (): void {
    actingAs(makeUserWith([Permission::EntryView->value]))
        ->get('/admin/entry/members/create')
        ->assertForbidden();
});

it('CSVを出力できる', function (): void {
    $member = Member::factory()->create();
    AttendanceLog::factory()->for($member)->create();

    actingAs(makeUserWith([Permission::EntryView->value]))
        ->get('/admin/entry/export')
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

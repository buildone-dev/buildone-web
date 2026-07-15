<?php

declare(strict_types=1);

use App\Core\Audit\Models\AuditLog;
use App\Core\Authorization\Permission;
use App\Features\Shift\Models\Shift;
use App\Features\Time\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('権限がないとシフト一覧を見られない', function (): void {
    actingAs(makeUserWith([]))->get('/admin/shift')->assertForbidden();
});

it('閲覧権限でシフト一覧を見られる', function (): void {
    actingAs(makeUserWith([Permission::ShiftView->value]))
        ->get('/admin/shift')
        ->assertOk()
        ->assertSee('今週のシフト');
});

it('シフトを登録でき監査に残る', function (): void {
    $employee = Employee::factory()->create();

    actingAs(makeUserWith([Permission::ShiftManage->value]))
        ->post('/admin/shift', [
            'employee_id' => $employee->id,
            'work_date' => today()->addDay()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '18:00',
            'note' => 'レジ担当',
        ])
        ->assertRedirect('/admin/shift');

    expect(Shift::query()->where('employee_id', $employee->id)->exists())->toBeTrue();
    expect(AuditLog::query()->where('action', 'shift.created')->exists())->toBeTrue();
});

it('終了時刻が開始より前だと登録できない', function (): void {
    $employee = Employee::factory()->create();

    actingAs(makeUserWith([Permission::ShiftManage->value]))
        ->post('/admin/shift', [
            'employee_id' => $employee->id,
            'work_date' => today()->format('Y-m-d'),
            'start_time' => '18:00',
            'end_time' => '09:00',
        ])
        ->assertSessionHasErrors('end_time');
});

it('シフトを削除できる', function (): void {
    $shift = Shift::factory()->create();

    actingAs(makeUserWith([Permission::ShiftManage->value]))
        ->delete("/admin/shift/{$shift->id}")
        ->assertRedirect('/admin/shift');

    expect(Shift::query()->whereKey($shift->id)->exists())->toBeFalse();
});

it('閲覧権限では登録画面を開けない', function (): void {
    actingAs(makeUserWith([Permission::ShiftView->value]))
        ->get('/admin/shift/create')
        ->assertForbidden();
});

it('CSVを出力できる', function (): void {
    Shift::factory()->create();

    actingAs(makeUserWith([Permission::ShiftView->value]))
        ->get('/admin/shift/export')
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

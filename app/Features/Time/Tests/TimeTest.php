<?php

declare(strict_types=1);

use App\Core\Audit\Models\AuditLog;
use App\Core\Authorization\Permission;
use App\Features\Time\Models\Employee;
use App\Features\Time\Models\TimeRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('権限がないと打刻画面を開けない', function (): void {
    actingAs(makeUserWith([]))->get('/admin/time/kiosk')->assertForbidden();
});

it('打刻画面を開ける（TimeView）', function (): void {
    actingAs(makeUserWith([Permission::TimeView->value]))
        ->get('/admin/time/kiosk')
        ->assertOk()
        ->assertSee('勤怠打刻');
});

it('打刻で出勤→退勤が切り替わり勤務時間が記録される', function (): void {
    $user = makeUserWith([Permission::TimeView->value]);
    $employee = Employee::factory()->create(['working' => false]);

    // 出勤
    actingAs($user)->post('/admin/time/punch', ['code' => $employee->code])->assertRedirect();
    expect($employee->fresh()->working)->toBeTrue();
    $record = TimeRecord::query()->where('employee_id', $employee->id)->first();
    expect($record)->not->toBeNull();
    expect($record->clock_out)->toBeNull();

    // 退勤
    actingAs($user)->post('/admin/time/punch', ['code' => $employee->code])->assertRedirect();
    expect($employee->fresh()->working)->toBeFalse();
    expect($record->fresh()->clock_out)->not->toBeNull();
    expect($record->fresh()->worked_minutes)->not->toBeNull();
});

it('未登録コードの打刻はエラーになる', function (): void {
    actingAs(makeUserWith([Permission::TimeView->value]))
        ->post('/admin/time/punch', ['code' => 'NOPE'])
        ->assertSessionHas('punch_error');

    expect(TimeRecord::query()->count())->toBe(0);
});

it('勤怠一覧に集計が表示される', function (): void {
    TimeRecord::factory()->count(2)->create();

    actingAs(makeUserWith([Permission::TimeView->value]))
        ->get('/admin/time')
        ->assertOk()
        ->assertSee('本日の勤務合計');
});

it('従業員を登録でき監査に残る', function (): void {
    actingAs(makeUserWith([Permission::TimeManage->value]))
        ->post('/admin/time/employees', ['name' => '佐藤 花子', 'code' => 'EMP-0001'])
        ->assertRedirect('/admin/time/employees');

    expect(Employee::query()->where('code', 'EMP-0001')->exists())->toBeTrue();
    expect(AuditLog::query()->where('action', 'employee.created')->exists())->toBeTrue();
});

it('閲覧権限では従業員登録画面を開けない', function (): void {
    actingAs(makeUserWith([Permission::TimeView->value]))
        ->get('/admin/time/employees/create')
        ->assertForbidden();
});

it('月次集計に従業員別の勤務時間が表示される', function (): void {
    $employee = Employee::factory()->create(['name' => '田中 太郎']);
    TimeRecord::factory()->for($employee)->create([
        'clock_in' => today()->startOfMonth()->setTime(9, 0),
        'clock_out' => today()->startOfMonth()->setTime(17, 0),
        'worked_minutes' => 480,
    ]);

    actingAs(makeUserWith([Permission::TimeView->value]))
        ->get('/admin/time/monthly?month='.today()->format('Y-m'))
        ->assertOk()
        ->assertSee('月次勤怠集計')
        ->assertSee('田中 太郎');
});

it('CSVを出力できる', function (): void {
    TimeRecord::factory()->create();

    actingAs(makeUserWith([Permission::TimeView->value]))
        ->get('/admin/time/export')
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

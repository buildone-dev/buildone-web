<?php

declare(strict_types=1);

use App\Core\Authorization\Permission;
use App\Features\Time\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('CSVで従業員を一括登録でき、ヘッダーと重複はスキップされる', function (): void {
    Employee::factory()->create(['code' => 'EMP-0001']);

    $csv = "名前,コード\n田中 太郎,EMP-0002\n佐藤 花子,EMP-0003\n重複さん,EMP-0001\n,EMP-0004\n";
    $file = UploadedFile::fake()->createWithContent('employees.csv', $csv);

    actingAs(makeUserWith([Permission::TimeManage->value]))
        ->post('/admin/time/employees/import', ['file' => $file])
        ->assertRedirect('/admin/time/employees');

    expect(Employee::query()->where('code', 'EMP-0002')->exists())->toBeTrue();
    expect(Employee::query()->where('code', 'EMP-0003')->exists())->toBeTrue();
    expect(Employee::query()->count())->toBe(3); // 既存1 + 新規2
});

it('閲覧権限だけでは取込できない', function (): void {
    $file = UploadedFile::fake()->createWithContent('employees.csv', "名前,コード\n田中,E1\n");

    actingAs(makeUserWith([Permission::TimeView->value]))
        ->post('/admin/time/employees/import', ['file' => $file])
        ->assertForbidden();
});

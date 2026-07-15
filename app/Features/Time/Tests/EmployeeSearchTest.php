<?php

declare(strict_types=1);

use App\Core\Authorization\Permission;
use App\Features\Time\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('従業員一覧を名前で検索できる', function (): void {
    Employee::factory()->create(['name' => '田中 太郎', 'code' => 'EMP-100']);
    Employee::factory()->create(['name' => '佐藤 花子', 'code' => 'EMP-200']);

    actingAs(makeUserWith([Permission::TimeView->value]))
        ->get('/admin/time/employees?q=田中')
        ->assertOk()
        ->assertSee('田中 太郎')
        ->assertDontSee('佐藤 花子');
});

it('コードでも検索できる', function (): void {
    Employee::factory()->create(['name' => '田中 太郎', 'code' => 'EMP-100']);
    Employee::factory()->create(['name' => '佐藤 花子', 'code' => 'EMP-200']);

    actingAs(makeUserWith([Permission::TimeView->value]))
        ->get('/admin/time/employees?q=EMP-200')
        ->assertOk()
        ->assertSee('佐藤 花子')
        ->assertDontSee('田中 太郎');
});

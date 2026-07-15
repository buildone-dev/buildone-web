<?php

declare(strict_types=1);

use App\Core\Authorization\Permission;
use App\Features\Time\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('未認証はダッシュボードを開けない', function (): void {
    get('/admin')->assertRedirect('/login');
});

it('権限のある業務Widgetがダッシュボードに表示される', function (): void {
    Employee::factory()->count(2)->create(['working' => true]);

    actingAs(makeUserWith([Permission::TimeView->value]))
        ->get('/admin')
        ->assertOk()
        ->assertSee('業務サマリー')
        ->assertSee('現在の出勤者');
});

it('権限のないWidgetは表示されない', function (): void {
    actingAs(makeUserWith([Permission::TimeView->value]))
        ->get('/admin')
        ->assertOk()
        ->assertDontSee('本日の予約')
        ->assertDontSee('今月の日報');
});

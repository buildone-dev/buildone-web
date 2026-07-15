<?php

declare(strict_types=1);

use App\Core\Audit\Models\AuditLog;
use App\Core\Authorization\Permission;
use App\Features\Reserve\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('権限がないと予約一覧を見られない', function (): void {
    actingAs(makeUserWith([]))->get('/admin/reserve')->assertForbidden();
});

it('閲覧権限で予約一覧を見られる', function (): void {
    actingAs(makeUserWith([Permission::ReserveView->value]))
        ->get('/admin/reserve')
        ->assertOk()
        ->assertSee('本日の予約');
});

it('予約を登録でき監査に残る', function (): void {
    actingAs(makeUserWith([Permission::ReserveManage->value]))
        ->post('/admin/reserve', [
            'customer_name' => '山田 太郎',
            'reserved_at' => now()->addDay()->format('Y-m-d H:i'),
            'party_size' => 4,
        ])
        ->assertRedirect('/admin/reserve');

    $reservation = Reservation::query()->where('customer_name', '山田 太郎')->first();
    expect($reservation)->not->toBeNull();
    expect($reservation->status)->toBe(Reservation::PENDING);
    expect(AuditLog::query()->where('action', 'reservation.created')->exists())->toBeTrue();
});

it('必須項目が未入力だと登録できない', function (): void {
    actingAs(makeUserWith([Permission::ReserveManage->value]))
        ->post('/admin/reserve', [])
        ->assertSessionHasErrors(['customer_name', 'reserved_at', 'party_size']);
});

it('予約を確定・キャンセルできる', function (): void {
    $user = makeUserWith([Permission::ReserveManage->value]);
    $reservation = Reservation::factory()->create(['status' => Reservation::PENDING]);

    actingAs($user)->patch("/admin/reserve/{$reservation->id}/confirm")->assertRedirect('/admin/reserve');
    expect($reservation->fresh()->status)->toBe(Reservation::CONFIRMED);

    actingAs($user)->patch("/admin/reserve/{$reservation->id}/cancel")->assertRedirect('/admin/reserve');
    expect($reservation->fresh()->status)->toBe(Reservation::CANCELLED);
});

it('閲覧権限では登録画面を開けない', function (): void {
    actingAs(makeUserWith([Permission::ReserveView->value]))
        ->get('/admin/reserve/create')
        ->assertForbidden();
});

it('CSVを出力できる', function (): void {
    Reservation::factory()->create();

    actingAs(makeUserWith([Permission::ReserveView->value]))
        ->get('/admin/reserve/export')
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

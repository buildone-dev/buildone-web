<?php

declare(strict_types=1);

use App\Core\Authorization\Permission;
use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Exceptions\ChatUnavailableException;
use App\Features\Chat\Responders\FakeResponder;
use App\Features\Report\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('権限がないと日報一覧を見られない', function (): void {
    actingAs(makeUserWith([]))->get('/admin/report')->assertForbidden();
});

it('閲覧権限で日報一覧を見られる', function (): void {
    actingAs(makeUserWith([Permission::ReportView->value]))
        ->get('/admin/report')
        ->assertOk()
        ->assertSee('今月の日報');
});

it('日報を作成できる', function (): void {
    $user = makeUserWith([Permission::ReportManage->value]);

    actingAs($user)
        ->post('/admin/report', ['report_date' => today()->format('Y-m-d'), 'body' => '本日の業務内容'])
        ->assertRedirect();

    $report = Report::query()->first();
    expect($report)->not->toBeNull();
    expect($report->user_id)->toBe($user->id);
});

it('本文が空だと作成できない', function (): void {
    actingAs(makeUserWith([Permission::ReportManage->value]))
        ->post('/admin/report', ['report_date' => today()->format('Y-m-d')])
        ->assertSessionHasErrors('body');
});

it('AI要約を生成して保存できる', function (): void {
    app()->bind(ChatResponder::class, fn () => new FakeResponder("・A社訪問\n・B社と商談"));
    $report = Report::factory()->create(['summary' => null]);

    actingAs(makeUserWith([Permission::ReportManage->value]))
        ->post("/admin/report/{$report->id}/summarize")
        ->assertRedirect();

    expect($report->fresh()->summary)->toContain('A社訪問');
});

it('AIが使えない場合は要約なしでエラー表示になる', function (): void {
    app()->bind(ChatResponder::class, fn () => new class implements ChatResponder
    {
        public function reply(array $messages): string
        {
            throw new ChatUnavailableException('停止中');
        }
    });
    $report = Report::factory()->create(['summary' => null]);

    actingAs(makeUserWith([Permission::ReportManage->value]))
        ->post("/admin/report/{$report->id}/summarize")
        ->assertSessionHas('summary_error');

    expect($report->fresh()->summary)->toBeNull();
});

it('閲覧権限では作成画面を開けない', function (): void {
    actingAs(makeUserWith([Permission::ReportView->value]))
        ->get('/admin/report/create')
        ->assertForbidden();
});

it('CSVを出力できる', function (): void {
    Report::factory()->create();

    actingAs(makeUserWith([Permission::ReportView->value]))
        ->get('/admin/report/export')
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

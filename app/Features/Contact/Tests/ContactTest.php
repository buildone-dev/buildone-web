<?php

declare(strict_types=1);

use App\Core\Authorization\Permission;
use App\Features\Contact\Mail\InquiryReceived;
use App\Features\Contact\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(TestCase::class, RefreshDatabase::class);

function validInquiry(array $overrides = []): array
{
    return array_merge([
        'company' => '株式会社サンプル',
        'contact_name' => '山田 太郎',
        'email' => 'taro@example.com',
        'phone' => '03-1234-5678',
        'current_task' => 'Excelで在庫管理',
        'improvement' => '自動化したい',
        'consent' => '1',
    ], $overrides);
}

it('問い合わせページを開ける', function (): void {
    get('/contact')->assertOk()->assertSee('お問い合わせ');
});

it('必須項目が未入力だと送信できない', function (): void {
    post('/contact', [])
        ->assertSessionHasErrors(['company', 'contact_name', 'email', 'current_task', 'improvement', 'consent']);

    expect(Inquiry::query()->count())->toBe(0);
});

it('メールアドレスの形式を検証する', function (): void {
    post('/contact', validInquiry(['email' => 'not-an-email']))
        ->assertSessionHasErrors('email');
});

it('個人情報に同意しないと送信できない', function (): void {
    post('/contact', validInquiry(['consent' => null]))
        ->assertSessionHasErrors('consent');

    expect(Inquiry::query()->count())->toBe(0);
});

it('正しく入力すると問い合わせが保存され完了表示になる', function (): void {
    post('/contact', validInquiry())
        ->assertRedirect('/contact')
        ->assertSessionHas('inquiry_sent', true);

    expect(Inquiry::query()->where('email', 'taro@example.com')->exists())->toBeTrue();
});

it('通知先が設定されていれば問い合わせ受信メールを送る', function (): void {
    Mail::fake();
    config(['buildone.contact.notify_email' => 'info@buildone.sakura.ne.jp']);

    post('/contact', validInquiry());

    Mail::assertSent(InquiryReceived::class, function ($mail) {
        return $mail->hasTo('info@buildone.sakura.ne.jp');
    });
});

it('通知先が未設定ならメールは送らない', function (): void {
    Mail::fake();
    config(['buildone.contact.notify_email' => null]);

    post('/contact', validInquiry());

    Mail::assertNothingSent();
});

it('LINE通知先が設定されていればLINE Messaging APIへpushする', function (): void {
    Http::fake(['https://api.line.me/*' => Http::response(['message' => 'ok'], 200)]);
    config(['services.line.channel_access_token' => 'test-token', 'services.line.notify_to' => 'test-user-id']);

    post('/contact', validInquiry());

    Http::assertSent(function (Request $request) {
        return $request->url() === 'https://api.line.me/v2/bot/message/push'
            && $request->hasHeader('Authorization', 'Bearer test-token')
            && $request['to'] === 'test-user-id';
    });
});

it('LINE通知先が未設定ならLINE Messaging APIへ送らない', function (): void {
    Http::fake();
    config(['services.line.channel_access_token' => null, 'services.line.notify_to' => null]);

    post('/contact', validInquiry());

    Http::assertNothingSent();
});

it('問い合わせから開発着手プロンプトが生成される', function (): void {
    post('/contact', validInquiry(['current_task' => '紙で勤怠を管理', 'improvement' => 'スマホ打刻したい']));

    $inquiry = Inquiry::query()->latest('id')->first();

    expect($inquiry->generated_prompt)
        ->toContain('BuildOne 開発着手プロンプト')
        ->toContain('紙で勤怠を管理')
        ->toContain('スマホ打刻したい')
        ->toContain('buildone:make-module');
});

it('チャット内容は同意した場合だけ添付される', function (): void {
    // 同意（チェック）あり → 保存される
    post('/contact', validInquiry(['attach_chat' => '1', 'chat_transcript' => 'ユーザー: 在庫管理を楽にしたい']));
    expect(Inquiry::query()->latest('id')->first()->chat_transcript)->toContain('在庫管理');

    // 同意なし → transcript を送っても保存されない
    post('/contact', validInquiry(['email' => 'b@example.com', 'chat_transcript' => 'ユーザー: 秘密の相談']));
    expect(Inquiry::query()->where('email', 'b@example.com')->first()->chat_transcript)->toBeNull();
});

it('管理者以外は問い合わせ一覧を見られない', function (): void {
    actingAs(makeUserWith([]))->get('/admin/inquiries')->assertForbidden();
});

it('権限があれば問い合わせ一覧を見られる', function (): void {
    Inquiry::factory()->create();

    actingAs(makeUserWith([Permission::InquiryView->value]))
        ->get('/admin/inquiries')
        ->assertOk();
});

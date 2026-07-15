<?php

declare(strict_types=1);

use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Exceptions\ChatUnavailableException;
use App\Features\Chat\Models\ChatMessage;
use App\Features\Chat\Responders\FakeResponder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\postJson;

uses(TestCase::class, RefreshDatabase::class);

it('正常回答の末尾に問い合わせ誘導が必ず含まれる', function (): void {
    app()->bind(ChatResponder::class, fn () => new FakeResponder('在庫管理システムなら対応可能です。'));

    postJson('/chat/message', ['messages' => [['role' => 'user', 'content' => '在庫管理できる？']]])
        ->assertOk()
        ->assertJson([
            'status' => 'answered',
            'answer' => '在庫管理システムなら対応可能です。',
            'show_contact_cta' => true,
        ])
        ->assertJsonPath('contact_cta.button_text', config('buildone.contact_cta.button_text'));
});

it('AIが応答できないエラー時でも問い合わせ誘導が含まれる', function (): void {
    app()->bind(ChatResponder::class, fn () => new class implements ChatResponder
    {
        public function reply(array $messages): string
        {
            throw new ChatUnavailableException('停止中');
        }
    });

    postJson('/chat/message', ['messages' => [['role' => 'user', 'content' => 'こんにちは']]])
        ->assertOk()
        ->assertJson(['status' => 'error', 'show_contact_cta' => true])
        ->assertJsonPath('contact_cta.title', config('buildone.contact_cta.title'));
});

it('やり取りしたチャットがすべて保存される', function (): void {
    app()->bind(ChatResponder::class, fn () => new FakeResponder('承知しました。'));

    postJson('/chat/message', [
        'conversation_id' => '11111111-1111-4111-8111-111111111111',
        'messages' => [['role' => 'user', 'content' => '勤怠管理をしたい']],
    ])->assertOk();

    expect(ChatMessage::query()->where('conversation_id', '11111111-1111-4111-8111-111111111111')->count())->toBe(2);
    expect(ChatMessage::query()->where('role', 'user')->where('content', '勤怠管理をしたい')->exists())->toBeTrue();
    expect(ChatMessage::query()->where('role', 'assistant')->where('content', '承知しました。')->exists())->toBeTrue();
});

it('メッセージが空だと拒否される', function (): void {
    postJson('/chat/message', ['messages' => []])->assertStatus(422);
});

it('role が不正だと拒否される', function (): void {
    postJson('/chat/message', ['messages' => [['role' => 'system', 'content' => 'x']]])->assertStatus(422);
});

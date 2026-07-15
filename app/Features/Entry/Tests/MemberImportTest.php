<?php

declare(strict_types=1);

use App\Core\Authorization\Permission;
use App\Features\Entry\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('CSVで利用者を一括登録でき、ヘッダーと重複はスキップされる', function (): void {
    Member::factory()->create(['code' => 'CARD-0001']);

    $csv = "名前,コード\n田中 太郎,CARD-0002\n佐藤 花子,CARD-0003\n重複さん,CARD-0001\n,CARD-0004\n";
    $file = UploadedFile::fake()->createWithContent('members.csv', $csv);

    actingAs(makeUserWith([Permission::EntryManage->value]))
        ->post('/admin/entry/members/import', ['file' => $file])
        ->assertRedirect('/admin/entry/members');

    // 田中・佐藤の2件が新規作成、ヘッダー/重複コード/名前空はスキップ
    expect(Member::query()->where('code', 'CARD-0002')->exists())->toBeTrue();
    expect(Member::query()->where('code', 'CARD-0003')->exists())->toBeTrue();
    expect(Member::query()->count())->toBe(3); // 既存1 + 新規2
});

it('閲覧権限だけでは取込できない', function (): void {
    $file = UploadedFile::fake()->createWithContent('members.csv', "名前,コード\n田中,C1\n");

    actingAs(makeUserWith([Permission::EntryView->value]))
        ->post('/admin/entry/members/import', ['file' => $file])
        ->assertForbidden();
});

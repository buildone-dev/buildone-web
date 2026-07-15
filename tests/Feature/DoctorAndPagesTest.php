<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

use function Pest\Laravel\get;

it('buildone:doctor が正常終了する', function (): void {
    $exit = Artisan::call('buildone:doctor');

    expect($exit)->toBe(0);
    expect(Artisan::output())->toContain('すべて正常です');
});

it('利用規約ページが表示できる', function (): void {
    get('/terms')->assertOk()->assertSee('利用規約');
});

<?php

declare(strict_types=1);

use function Pest\Laravel\get;

it('UI Kit プレビューが表示できる（非本番）', function (): void {
    get('/ui-kit')
        ->assertOk()
        ->assertSee('UI Kit プレビュー')
        ->assertSee('Primary')
        ->assertSee('Empty State');
});

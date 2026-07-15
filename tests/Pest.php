<?php

declare(strict_types=1);
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| tests/ 配下の Feature/Unit テストに TestCase をバインドする。
| モジュール（app/Core, app/Features）のテストは各ファイルで
| uses(Tests\TestCase::class, RefreshDatabase::class) を宣言する。
|
*/

uses(TestCase::class)->in('Feature');

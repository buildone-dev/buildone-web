<?php

declare(strict_types=1);

namespace App\Console\Commands\BuildOne;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * BuildOne モジュール（Feature First の縦割りスライス）を生成する。
 *
 *   php artisan buildone:new-module Task
 *   php artisan buildone:new-module Auth --group=Core
 */
final class NewModuleCommand extends Command
{
    protected $signature = 'buildone:make-module
        {name : モジュール名（StudlyCase 例: Task）}
        {--group=Features : 配置グループ（Core または Features）}';

    /**
     * @var array<int, string>
     */
    protected $aliases = ['buildone:new-module'];

    protected $description = 'BuildOne モジュール（Laravel標準寄りの Feature First 構成）を生成する';

    public function handle(Filesystem $files): int
    {
        $name = Str::studly((string) $this->argument('name'));
        $group = Str::studly((string) $this->option('group'));

        if (! in_array($group, ['Core', 'Features'], true)) {
            $this->components->error('--group には Core または Features を指定してください。');

            return self::FAILURE;
        }

        $moduleRoot = app_path("{$group}/{$name}");

        if ($files->isDirectory($moduleRoot)) {
            $this->components->error("モジュール {$group}/{$name} は既に存在します。");

            return self::FAILURE;
        }

        $snake = Str::snake($name);
        $table = Str::plural($snake);

        $replacements = [
            '__NAMESPACE__' => "App\\{$group}\\{$name}",
            '__MODULE__' => $name,
            '__MODULE_LOWER__' => Str::lower($name),
            '__MODULE_SNAKE__' => $snake,
            '__TABLE__' => $table,
        ];

        $timestamp = now()->format('Y_m_d_His');

        /** @var array<string, string> $map スタブ名 => モジュール内の出力先 */
        $map = [
            'provider.stub' => "{$name}ServiceProvider.php",
            'model.stub' => "Models/{$name}.php",
            'factory.stub' => "Database/Factories/{$name}Factory.php",
            'migration.stub' => "Database/Migrations/{$timestamp}_create_{$table}_table.php",
            'controller.stub' => "Http/Controllers/{$name}Controller.php",
            'routes.stub' => 'Routes/web.php',
            'view.stub' => 'Views/index.blade.php',
            'test.stub' => "Tests/{$name}Test.php",
        ];

        $stubBase = base_path('stubs/buildone/module');

        foreach ($map as $stub => $destination) {
            $content = strtr($files->get("{$stubBase}/{$stub}"), $replacements);
            $target = "{$moduleRoot}/{$destination}";
            $files->ensureDirectoryExists(dirname($target));
            $files->put($target, $content);
            $this->components->task(str_replace(base_path().DIRECTORY_SEPARATOR, '', $target));
        }

        $this->newLine();
        $this->components->info("モジュール {$group}/{$name} を生成しました。");
        $this->components->bulletList([
            "php artisan migrate  … テーブル {$table} を作成",
            "/{$table} を開くと一覧が表示されます",
        ]);

        return self::SUCCESS;
    }
}

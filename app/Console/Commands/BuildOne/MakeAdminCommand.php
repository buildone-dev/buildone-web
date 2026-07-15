<?php

declare(strict_types=1);

namespace App\Console\Commands\BuildOne;

use App\Core\Authorization\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * 初期管理者（super_admin）を作成する。パスワードはランダム生成し一度だけ表示する。
 *
 *   php artisan buildone:make-admin admin@example.com --name="運営者"
 */
final class MakeAdminCommand extends Command
{
    protected $signature = 'buildone:make-admin {email : 管理者のメールアドレス} {--name=管理者 : 表示名}';

    protected $description = '初期管理者ユーザー（super_admin）を作成する';

    public function handle(): int
    {
        $role = Role::query()->where('name', Role::SUPER_ADMIN)->first();

        if ($role === null) {
            $this->components->error('先に `php artisan db:seed --class=Database\\Seeders\\RoleSeeder` でロールを投入してください。');

            return self::FAILURE;
        }

        $email = (string) $this->argument('email');
        $password = Str::password(16);

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => (string) $this->option('name'),
                'password' => Hash::make($password),
                'role_id' => $role->id,
                'is_active' => true,
            ],
        );

        $this->components->info("管理者を作成しました: {$user->email}");
        $this->components->warn('初期パスワード（この画面にのみ表示。安全に保管し、初回ログイン後に変更してください）:');
        $this->line("  {$password}");

        return self::SUCCESS;
    }
}

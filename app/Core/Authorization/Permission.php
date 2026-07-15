<?php

declare(strict_types=1);

namespace App\Core\Authorization;

/**
 * 権限の一元管理。文字列を散在させず、この enum のみを参照する。
 */
enum Permission: string
{
    case UsersView = 'users.view';
    case UsersManage = 'users.manage';
    case RolesManage = 'roles.manage';
    case AuditView = 'audit.view';
    case SettingsManage = 'settings.manage';
    case EntryView = 'entry.view';
    case EntryManage = 'entry.manage';
    case InquiryView = 'inquiry.view';

    public function label(): string
    {
        return match ($this) {
            self::UsersView => 'ユーザー閲覧',
            self::UsersManage => 'ユーザー管理',
            self::RolesManage => 'ロール・権限管理',
            self::AuditView => '監査ログ閲覧',
            self::SettingsManage => '設定管理',
            self::EntryView => '入退室 閲覧・打刻',
            self::EntryManage => '入退室 利用者管理',
            self::InquiryView => '問い合わせ閲覧',
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }
}

<?php

declare(strict_types=1);

namespace App\Core\Settings\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * キー・バリュー形式のアプリ設定。管理画面から編集し、環境変数より優先して参照する。
 *
 * @property string $key
 * @property string|null $value
 */
class Setting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['key', 'value'];
}

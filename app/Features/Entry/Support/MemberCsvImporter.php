<?php

declare(strict_types=1);

namespace App\Features\Entry\Support;

use App\Features\Entry\Models\Member;

/**
 * CSV（1列目=名前, 2列目=コード）から利用者を一括登録する。
 * 既存コードや空行はスキップする。
 */
final class MemberCsvImporter
{
    /**
     * @return array{created: int, skipped: int}
     */
    public function import(string $path): array
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return ['created' => 0, 'skipped' => 0];
        }

        $created = 0;
        $skipped = 0;
        $line = 0;

        while (($data = fgetcsv($handle)) !== false) {
            $line++;
            $name = $this->clean((string) ($data[0] ?? ''));
            $code = $this->clean((string) ($data[1] ?? ''));

            if ($line === 1 && $this->isHeader($name, $code)) {
                continue;
            }

            if ($name === '' || $code === '') {
                $skipped++;

                continue;
            }

            if (Member::query()->where('code', $code)->exists()) {
                $skipped++;

                continue;
            }

            Member::query()->create(['name' => $name, 'code' => $code, 'is_active' => true]);
            $created++;
        }

        fclose($handle);

        return ['created' => $created, 'skipped' => $skipped];
    }

    private function clean(string $value): string
    {
        return trim(preg_replace('/^\xEF\xBB\xBF/', '', $value) ?? '');
    }

    private function isHeader(string $name, string $code): bool
    {
        return in_array(mb_strtolower($name), ['name', '名前', '氏名'], true)
            || in_array(mb_strtolower($code), ['code', 'コード'], true);
    }
}

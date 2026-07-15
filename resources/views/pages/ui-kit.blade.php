<x-layouts.app title="UI Kit">
    <x-slot:header>
        <x-breadcrumb :items="[
            ['label' => 'BuildOne', 'href' => '/'],
            ['label' => 'UI Kit'],
        ]" />
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-ink">UI Kit プレビュー</h2>
        <p class="mt-1 text-sm text-slate-500">BuildOne Framework の共通コンポーネント一覧（M2）。</p>
    </x-slot:header>

    <div class="space-y-8">
        {{-- Stat cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="本日の入室" value="128" delta="12%" trend="up" />
            <x-stat-card label="在室中" value="42" />
            <x-stat-card label="アラート" value="0" delta="0%" trend="up" />
            <x-stat-card label="離脱率" value="3.2%" delta="0.4%" trend="down" />
        </div>

        {{-- Buttons & badges --}}
        <x-card title="Buttons / Badges">
            <div class="flex flex-wrap items-center gap-3">
                <x-button variant="primary">Primary</x-button>
                <x-button variant="secondary">Secondary</x-button>
                <x-button variant="ghost">Ghost</x-button>
                <x-button variant="danger">Danger</x-button>
                <x-button variant="primary" :loading="true">Loading</x-button>
                <x-button variant="primary" disabled>Disabled</x-button>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <x-badge>Neutral</x-badge>
                <x-badge variant="brand">Brand</x-badge>
                <x-badge variant="success">開発予定</x-badge>
                <x-badge variant="warning">Warning</x-badge>
                <x-badge variant="danger">無効</x-badge>
            </div>
        </x-card>

        {{-- Alerts --}}
        <x-card title="Alerts">
            <div class="space-y-3">
                <x-alert variant="info" title="お知らせ">情報メッセージです。</x-alert>
                <x-alert variant="success" title="成功">保存しました。</x-alert>
                <x-alert variant="warning" title="注意">未保存の変更があります。</x-alert>
                <x-alert variant="danger" title="エラー">処理に失敗しました。</x-alert>
            </div>
        </x-card>

        {{-- Form --}}
        <x-card title="Form">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form-field label="お名前" for="f-name" :required="true" hint="フルネームで入力してください">
                    <x-input id="f-name" placeholder="山田 太郎" />
                </x-form-field>
                <x-form-field label="メール" for="f-mail" :error="['メールアドレスの形式が正しくありません']">
                    <x-input id="f-mail" type="email" :error="true" value="invalid" />
                </x-form-field>
                <x-form-field label="部署" for="f-dept">
                    <x-select id="f-dept">
                        <option>営業部</option>
                        <option>開発部</option>
                    </x-select>
                </x-form-field>
                <x-form-field label="備考" for="f-note">
                    <x-textarea id="f-note" placeholder="自由記入" />
                </x-form-field>
                <div class="flex items-center gap-6">
                    <x-checkbox label="有効" checked />
                    <x-radio name="plan" label="月額" checked />
                    <x-radio name="plan" label="年額" />
                </div>
            </div>
        </x-card>

        {{-- Table --}}
        <x-card title="Table">
            <x-slot:actions>
                <x-button size="sm" data-modal-open="demo-modal">モーダルを開く</x-button>
            </x-slot:actions>
            <x-table>
                <x-slot:head>
                    <th class="px-4 py-3">名前</th>
                    <th class="px-4 py-3">状態</th>
                    <th class="px-4 py-3">時刻</th>
                </x-slot:head>
                <tr><td class="px-4 py-3">田中 さくら</td><td class="px-4 py-3"><x-badge variant="success">入室</x-badge></td><td class="px-4 py-3 text-slate-500">09:02</td></tr>
                <tr><td class="px-4 py-3">佐藤 健太</td><td class="px-4 py-3"><x-badge variant="brand">記録済</x-badge></td><td class="px-4 py-3 text-slate-500">09:05</td></tr>
            </x-table>
        </x-card>

        {{-- Empty / Loading / Skeleton --}}
        <div class="grid gap-4 lg:grid-cols-3">
            <x-card title="Empty State">
                <x-empty-state title="まだ記録がありません" description="最初のデータを追加してください。">
                    <x-slot:action>
                        <x-button size="sm">追加する</x-button>
                    </x-slot:action>
                </x-empty-state>
            </x-card>
            <x-card title="Loading / Dropdown">
                <x-loading>読み込み中…</x-loading>
                <div class="mt-4">
                    <x-dropdown id="demo-dd">
                        <x-slot:trigger>
                            <x-button variant="secondary" size="sm">メニュー ▾</x-button>
                        </x-slot:trigger>
                        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">編集</a>
                        <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">削除</a>
                    </x-dropdown>
                </div>
            </x-card>
            <x-card title="Skeleton">
                <x-skeleton :lines="4" />
            </x-card>
        </div>
    </div>

    <x-modal id="demo-modal" title="確認">
        本当に実行しますか？
        <x-slot:footer>
            <x-button variant="ghost" data-modal-close>キャンセル</x-button>
            <x-button variant="primary" data-modal-close>実行</x-button>
        </x-slot:footer>
    </x-modal>
</x-layouts.app>

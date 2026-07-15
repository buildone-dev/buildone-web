<x-layouts.app title="利用者">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => '入退室', 'href' => route('entry.index')], ['label' => '利用者']]" />
        <div class="mt-2 flex items-center justify-between gap-4">
            <h2 class="text-2xl font-bold tracking-tight text-ink">利用者</h2>
            @can(\App\Core\Authorization\Permission::EntryManage->value)
                <x-link-button :href="route('entry.members.create')" size="sm">利用者を登録</x-link-button>
            @endcan
        </div>
    </x-slot:header>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    @can(\App\Core\Authorization\Permission::EntryManage->value)
        <x-card class="mb-4">
            <form method="POST" action="{{ route('entry.members.import') }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                @csrf
                <span class="text-sm font-medium text-ink">CSV一括取込</span>
                <input type="file" name="file" accept=".csv,text/csv" required
                    class="text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand/10 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand hover:file:bg-brand/20" />
                <x-button type="submit" variant="secondary" size="sm">取り込む</x-button>
                <span class="text-xs text-slate-400">形式: 1列目=名前, 2列目=コード（ヘッダー行可）</span>
            </form>
            @error('file')
                <x-validation-error :messages="$message" />
            @enderror
        </x-card>
    @endcan

    <div class="mb-4"><x-search-box placeholder="名前・コードで検索" /></div>

    @if ($members->isEmpty())
        <x-empty-state :title="request('q') ? '該当する利用者がいません' : '利用者がいません'" description="QR・ICカードのコードを持つ利用者を登録してください。" />
    @else
        <x-table>
            <x-slot:head>
                <th class="px-4 py-3">名前</th>
                <th class="px-4 py-3">コード</th>
                <th class="px-4 py-3">在室</th>
                <th class="px-4 py-3">状態</th>
                <th class="px-4 py-3 text-right">操作</th>
            </x-slot:head>
            @foreach ($members as $member)
                <tr>
                    <td class="px-4 py-3 font-medium text-ink">{{ $member->name }}</td>
                    <td class="px-4 py-3 font-mono text-sm text-slate-500">{{ $member->code }}</td>
                    <td class="px-4 py-3">
                        @if ($member->present)
                            <x-badge variant="success">在室</x-badge>
                        @else
                            <x-badge>不在</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($member->is_active)
                            <x-badge variant="brand">有効</x-badge>
                        @else
                            <x-badge variant="danger">無効</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            @can(\App\Core\Authorization\Permission::EntryManage->value)
                                <x-link-button :href="route('entry.members.edit', $member)" variant="secondary" size="sm">編集</x-link-button>
                                <form method="POST" action="{{ route('entry.members.toggle', $member) }}">
                                    @csrf @method('PATCH')
                                    <x-button type="submit" variant="ghost" size="sm">{{ $member->is_active ? '無効化' : '有効化' }}</x-button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <x-pagination :paginator="$members" />
    @endif
</x-layouts.app>

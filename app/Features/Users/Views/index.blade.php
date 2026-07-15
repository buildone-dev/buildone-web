<x-layouts.app title="ユーザー">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => 'ユーザー']]" />
        <div class="mt-2 flex items-center justify-between gap-4">
            <h2 class="text-2xl font-bold tracking-tight text-ink">ユーザー</h2>
            @can(\App\Core\Authorization\Permission::UsersManage->value)
                <x-link-button :href="route('users.create')" size="sm">新規作成</x-link-button>
            @endcan
        </div>
    </x-slot:header>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    @if ($users->isEmpty())
        <x-empty-state title="ユーザーがいません" />
    @else
        <x-table>
            <x-slot:head>
                <th class="px-4 py-3">名前</th>
                <th class="px-4 py-3">メール</th>
                <th class="px-4 py-3">ロール</th>
                <th class="px-4 py-3">状態</th>
                <th class="px-4 py-3 text-right">操作</th>
            </x-slot:head>
            @foreach ($users as $user)
                <tr>
                    <td class="px-4 py-3 font-medium text-ink">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $user->email }}</td>
                    <td class="px-4 py-3">{{ $user->role?->label ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if ($user->is_active)
                            <x-badge variant="success">有効</x-badge>
                        @else
                            <x-badge variant="danger">無効</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            @can(\App\Core\Authorization\Permission::UsersManage->value)
                                <x-link-button :href="route('users.edit', $user)" variant="secondary" size="sm">編集</x-link-button>
                                <form method="POST" action="{{ route('users.toggle', $user) }}">
                                    @csrf @method('PATCH')
                                    <x-button type="submit" variant="ghost" size="sm">{{ $user->is_active ? '無効化' : '有効化' }}</x-button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <x-pagination :paginator="$users" />
    @endif
</x-layouts.app>

<x-layouts.app title="ロール・権限">
    <x-slot:header>
        <x-breadcrumb :items="[['label' => 'ダッシュボード', 'href' => route('dashboard')], ['label' => 'ロール・権限']]" />
        <div class="mt-2 flex items-center justify-between gap-4">
            <h2 class="text-2xl font-bold tracking-tight text-ink">ロール・権限</h2>
            <x-link-button :href="route('roles.create')" size="sm">ロールを作成</x-link-button>
        </div>
    </x-slot:header>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif
    @if (session('role_error'))
        <x-alert variant="danger" class="mb-4">{{ session('role_error') }}</x-alert>
    @endif

    <x-table>
        <x-slot:head>
            <th class="px-4 py-3">ロール</th>
            <th class="px-4 py-3">識別子</th>
            <th class="px-4 py-3">権限数</th>
            <th class="px-4 py-3">ユーザー数</th>
            <th class="px-4 py-3 text-right">操作</th>
        </x-slot:head>
        @foreach ($roles as $role)
            <tr>
                <td class="px-4 py-3 font-medium text-ink">
                    {{ $role->label }}
                    @if ($role->name === \App\Core\Authorization\Models\Role::SUPER_ADMIN)
                        <x-badge variant="brand" class="ml-1">全権限</x-badge>
                    @endif
                </td>
                <td class="px-4 py-3 font-mono text-sm text-slate-500">{{ $role->name }}</td>
                <td class="px-4 py-3 text-slate-600">
                    {{ $role->name === \App\Core\Authorization\Models\Role::SUPER_ADMIN ? 'すべて' : count($role->permissions ?? []) }}
                </td>
                <td class="px-4 py-3 text-slate-600">{{ $role->users_count }}名</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <x-link-button :href="route('roles.edit', $role)" variant="secondary" size="sm">編集</x-link-button>
                        @if ($role->name !== \App\Core\Authorization\Models\Role::SUPER_ADMIN)
                            <form method="POST" action="{{ route('roles.destroy', $role) }}" onsubmit="return confirm('このロールを削除しますか？');">
                                @csrf @method('DELETE')
                                <x-button type="submit" variant="ghost" size="sm">削除</x-button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table>
</x-layouts.app>

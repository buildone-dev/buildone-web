// ==========================================================================
// BuildOne Framework — 最小限のUI挙動（外部ライブラリ不使用）
// ==========================================================================

// --- 公開LP用ハンバーガーメニュー ---
const marketingToggle = document.getElementById('menu-toggle');
const marketingMenu = document.getElementById('mobile-menu');

if (marketingToggle && marketingMenu) {
    marketingToggle.addEventListener('click', () => {
        const open = marketingToggle.getAttribute('aria-expanded') === 'true';
        marketingToggle.setAttribute('aria-expanded', String(!open));
        marketingMenu.classList.toggle('hidden');
        marketingToggle.querySelector('[data-icon="open"]')?.classList.toggle('hidden');
        marketingToggle.querySelector('[data-icon="close"]')?.classList.toggle('hidden');
    });

    marketingMenu.querySelectorAll('a').forEach((a) =>
        a.addEventListener('click', () => {
            marketingToggle.setAttribute('aria-expanded', 'false');
            marketingMenu.classList.add('hidden');
            marketingToggle.querySelector('[data-icon="open"]')?.classList.remove('hidden');
            marketingToggle.querySelector('[data-icon="close"]')?.classList.add('hidden');
        }),
    );
}

// --- 管理画面サイドバーのモバイル開閉 ---
document.querySelectorAll('[data-sidebar-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const sidebar = document.getElementById('app-sidebar');
        const backdrop = document.getElementById('app-sidebar-backdrop');
        sidebar?.classList.toggle('-translate-x-full');
        backdrop?.classList.toggle('hidden');
    });
});
document.getElementById('app-sidebar-backdrop')?.addEventListener('click', () => {
    document.getElementById('app-sidebar')?.classList.add('-translate-x-full');
    document.getElementById('app-sidebar-backdrop')?.classList.add('hidden');
});

// --- ドロップダウン（data-dropdown-toggle が data-dropdown-target を開閉） ---
document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-dropdown-toggle]');
    if (trigger) {
        const id = trigger.getAttribute('data-dropdown-toggle');
        const panel = document.getElementById(id);
        const isOpen = panel && !panel.classList.contains('hidden');
        // 一旦すべて閉じる
        document.querySelectorAll('[data-dropdown-panel]').forEach((p) => p.classList.add('hidden'));
        if (panel && !isOpen) {
            panel.classList.remove('hidden');
            trigger.setAttribute('aria-expanded', 'true');
        }
        return;
    }
    // パネル外クリックで閉じる
    if (!e.target.closest('[data-dropdown-panel]')) {
        document.querySelectorAll('[data-dropdown-panel]').forEach((p) => p.classList.add('hidden'));
    }
});

// --- モーダル（data-modal-open="id" で開き、data-modal-close で閉じる） ---
function openModal(id) {
    document.getElementById(id)?.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}
function closeModal(el) {
    el?.closest('[data-modal]')?.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}
document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-modal-open]');
    if (opener) {
        openModal(opener.getAttribute('data-modal-open'));
        return;
    }
    if (e.target.closest('[data-modal-close]') || e.target.hasAttribute('data-modal-backdrop')) {
        closeModal(e.target);
    }
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('[data-modal]:not(.hidden)').forEach((m) => m.classList.add('hidden'));
        document.body.classList.remove('overflow-hidden');
    }
});

// --- トースト（window.toast(message, type) で表示） ---
window.toast = function (message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const colors = {
        success: 'border-l-4 border-[#22C55E]',
        error: 'border-l-4 border-red-500',
        info: 'border-l-4 border-[#2563EB]',
    };
    const el = document.createElement('div');
    el.className =
        'pointer-events-auto rounded-lg bg-white px-4 py-3 text-sm text-ink shadow-lg ring-1 ring-slate-200 ' +
        (colors[type] || colors.info);
    el.setAttribute('role', 'status');
    el.textContent = message;
    container.appendChild(el);
    setTimeout(() => {
        el.style.transition = 'opacity .3s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 300);
    }, 3500);
};

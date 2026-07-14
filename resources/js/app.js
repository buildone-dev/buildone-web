// モバイル用ハンバーガーメニュー
const toggle = document.getElementById('menu-toggle');
const menu = document.getElementById('mobile-menu');

if (toggle && menu) {
    toggle.addEventListener('click', () => {
        const open = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!open));
        menu.classList.toggle('hidden');
        toggle.querySelector('[data-icon="open"]')?.classList.toggle('hidden');
        toggle.querySelector('[data-icon="close"]')?.classList.toggle('hidden');
    });

    // メニュー内リンクを押したら閉じる
    menu.querySelectorAll('a').forEach((a) =>
        a.addEventListener('click', () => {
            toggle.setAttribute('aria-expanded', 'false');
            menu.classList.add('hidden');
            toggle.querySelector('[data-icon="open"]')?.classList.remove('hidden');
            toggle.querySelector('[data-icon="close"]')?.classList.add('hidden');
        }),
    );
}

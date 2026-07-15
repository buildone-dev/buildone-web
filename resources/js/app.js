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

// --- チャットウィジェット ---
(function () {
    const widget = document.getElementById('chat-widget');
    if (!widget) return;

    const launcher = document.getElementById('chat-launcher');
    const panel = document.getElementById('chat-panel');
    const closeBtn = document.getElementById('chat-close');
    const messagesEl = document.getElementById('chat-messages');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const endpoint = widget.dataset.endpoint;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const history = [];
    const conversationId =
        window.crypto?.randomUUID?.() ||
        'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
            const r = (Math.random() * 16) | 0;
            return (c === 'x' ? r : (r & 0x3) | 0x8).toString(16);
        });
    let started = false;
    let busy = false;

    function open() {
        panel.classList.remove('hidden');
        launcher.classList.add('hidden');
        if (!started) {
            addBubble('assistant', widget.dataset.greeting);
            started = true;
        }
        input.focus();
    }
    function close() {
        panel.classList.add('hidden');
        launcher.classList.remove('hidden');
    }

    function addBubble(role, text) {
        const wrap = document.createElement('div');
        wrap.className = role === 'user' ? 'flex justify-end' : 'flex justify-start';
        const bubble = document.createElement('div');
        bubble.className =
            role === 'user'
                ? 'max-w-[85%] whitespace-pre-wrap rounded-2xl rounded-br-sm bg-brand px-3.5 py-2 text-white'
                : 'max-w-[85%] whitespace-pre-wrap rounded-2xl rounded-bl-sm bg-white px-3.5 py-2 text-ink ring-1 ring-slate-200';
        bubble.textContent = text;
        wrap.appendChild(bubble);
        messagesEl.appendChild(wrap);
        messagesEl.scrollTop = messagesEl.scrollHeight;
        return bubble;
    }

    function addCta(cta) {
        // 問い合わせフォーム表示中は誘導カードを重複表示しない
        const modal = document.getElementById('inquiry-modal');
        if (modal && !modal.classList.contains('hidden')) return;

        const card = document.createElement('div');
        card.className = 'rounded-2xl border border-brand/30 bg-brand/5 p-3.5';
        const title = document.createElement('p');
        title.className = 'text-sm font-bold text-ink';
        title.textContent = cta.title;
        const msg = document.createElement('p');
        msg.className = 'mt-1 text-xs leading-relaxed text-slate-600';
        msg.textContent = cta.message;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className =
            'mt-3 inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark';
        btn.textContent = cta.button_text;
        btn.addEventListener('click', openInquiryFromChat);
        card.append(title, msg, btn);
        messagesEl.appendChild(card);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function openInquiryFromChat() {
        const transcript = history
            .map((x) => (x.role === 'user' ? 'ユーザー: ' : 'アシスタント: ') + x.content)
            .join('\n');
        const hidden = document.getElementById('inquiry-chat-transcript');
        if (hidden) hidden.value = transcript;
        const convField = document.getElementById('inquiry-conversation-id');
        if (convField) convField.value = conversationId;

        const lastUser = [...history].reverse().find((x) => x.role === 'user');
        const taskField = document.getElementById('current_task');
        if (lastUser && taskField && !taskField.value) taskField.value = lastUser.content;

        const modal = document.getElementById('inquiry-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    async function send(text) {
        if (busy || !text) return;
        busy = true;
        addBubble('user', text);
        history.push({ role: 'user', content: text });
        const typing = addBubble('assistant', '…');
        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
                body: JSON.stringify({ conversation_id: conversationId, messages: history }),
            });
            const data = await res.json();
            typing.textContent = data.answer || '（応答がありませんでした）';
            history.push({ role: 'assistant', content: data.answer || '' });
            if (data.show_contact_cta && data.contact_cta) addCta(data.contact_cta);
        } catch (e) {
            typing.textContent = '通信エラーが発生しました。お手数ですが下記からお問い合わせください。';
        } finally {
            busy = false;
            input.focus();
        }
    }

    launcher.addEventListener('click', open);
    closeBtn.addEventListener('click', close);
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;
        input.value = '';
        send(text);
    });
})();

// --- 問い合わせフォーム 二重送信防止 ---
document.querySelectorAll('[data-inquiry-form]').forEach((f) => {
    f.addEventListener('submit', () => {
        const btn = f.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.textContent = '送信中…';
        }
    });
});

// --- QRカメラ読み取り（BarcodeDetector API。対応端末のみ） ---
(function () {
    const root = document.getElementById('qr-scanner');
    if (!root) return;

    const startBtn = document.getElementById('qr-start');
    const cam = document.getElementById('qr-cam');
    const video = document.getElementById('qr-video');
    const status = document.getElementById('qr-status');
    const input = document.getElementById(root.dataset.input || 'code');
    const form = input ? input.closest('form') : null;

    if (!('BarcodeDetector' in window) || !navigator.mediaDevices?.getUserMedia) {
        if (startBtn) startBtn.style.display = 'none';
        if (status) status.textContent = 'この端末はカメラ読み取りに非対応です（手入力をご利用ください）。';
        return;
    }

    let stream = null;
    let detector = null;
    let scanning = false;

    function stop() {
        scanning = false;
        if (startBtn) startBtn.textContent = '📷 カメラで読み取る';
        cam?.classList.add('hidden');
        stream?.getTracks().forEach((t) => t.stop());
        stream = null;
    }

    async function scan() {
        if (!scanning || !detector) return;
        try {
            const codes = await detector.detect(video);
            if (codes.length > 0 && codes[0].rawValue) {
                if (input) input.value = codes[0].rawValue;
                stop();
                if (form) (form.requestSubmit ? form.requestSubmit() : form.submit());
                return;
            }
        } catch (e) {
            /* 一時的な検出失敗は無視して継続 */
        }
        requestAnimationFrame(scan);
    }

    startBtn?.addEventListener('click', async () => {
        if (scanning) { stop(); return; }
        try {
            status.textContent = '';
            detector = new window.BarcodeDetector({ formats: ['qr_code'] });
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            video.srcObject = stream;
            await video.play();
            cam.classList.remove('hidden');
            scanning = true;
            startBtn.textContent = 'カメラを止める';
            requestAnimationFrame(scan);
        } catch (e) {
            status.textContent = 'カメラを起動できませんでした。権限をご確認ください。';
            stop();
        }
    });
})();

// --- クリップボードにコピー（data-copy="対象要素ID"） ---
document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-copy]');
    if (!trigger) return;
    const target = document.getElementById(trigger.getAttribute('data-copy'));
    if (!target) return;
    const text = target.textContent || '';
    navigator.clipboard?.writeText(text).then(() => {
        const original = trigger.textContent;
        trigger.textContent = 'コピーしました';
        setTimeout(() => {
            trigger.textContent = original;
        }, 1500);
    });
});

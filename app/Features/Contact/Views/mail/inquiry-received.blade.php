新しいお問い合わせが届きました。

────────────────────────────
■ 会社・事業者名: {{ $inquiry->company }}
■ ご担当者: {{ $inquiry->contact_name }}
■ メール: {{ $inquiry->email }}
@if ($inquiry->phone)
■ 電話: {{ $inquiry->phone }}
@endif
■ 受信日時: {{ $inquiry->created_at?->format('Y-m-d H:i') }}
────────────────────────────

【現在困っている業務】
{{ $inquiry->current_task }}

【改善したい内容】
{{ $inquiry->improvement }}
@if ($inquiry->chat_transcript)

【添付されたチャット内容】
{{ $inquiry->chat_transcript }}
@endif
@if ($inquiry->generated_prompt)

────────────────────────────
【開発着手プロンプト（AIに渡してすぐ着手できます）】
────────────────────────────
{{ $inquiry->generated_prompt }}
@endif

管理画面: {{ route('inquiries.index') }}

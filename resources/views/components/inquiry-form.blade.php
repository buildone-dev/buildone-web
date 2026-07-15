{{-- 問い合わせフォーム。/contact ページとモーダルの両方で再利用する。 --}}
{{-- エラーページ等 $errors が共有されない文脈でも動くよう保険をかける --}}
@php($errors ??= new \Illuminate\Support\ViewErrorBag)
<form method="POST" action="{{ route('contact.store') }}" data-inquiry-form class="space-y-4">
    @csrf

    <div class="grid gap-4 sm:grid-cols-2">
        <x-form-field label="会社名・事業者名" for="company" :required="true" :error="$errors->get('company')">
            <x-input id="company" name="company" value="{{ old('company') }}" :error="$errors->has('company')" required />
        </x-form-field>
        <x-form-field label="担当者名" for="contact_name" :required="true" :error="$errors->get('contact_name')">
            <x-input id="contact_name" name="contact_name" value="{{ old('contact_name') }}" :error="$errors->has('contact_name')" required />
        </x-form-field>
        <x-form-field label="メールアドレス" for="email" :required="true" :error="$errors->get('email')">
            <x-input id="email" name="email" type="email" value="{{ old('email') }}" :error="$errors->has('email')" required />
        </x-form-field>
        <x-form-field label="電話番号（任意）" for="phone" :error="$errors->get('phone')">
            <x-input id="phone" name="phone" value="{{ old('phone') }}" :error="$errors->has('phone')" />
        </x-form-field>
    </div>

    <x-form-field label="現在困っている業務" for="current_task" :required="true" :error="$errors->get('current_task')">
        <x-textarea id="current_task" name="current_task" rows="3" :error="$errors->has('current_task')" placeholder="例：Excelと紙で在庫を管理していて手間がかかる">{{ old('current_task') }}</x-textarea>
    </x-form-field>

    <x-form-field label="改善したい内容" for="improvement" :required="true" :error="$errors->get('improvement')">
        <x-textarea id="improvement" name="improvement" rows="3" :error="$errors->has('improvement')" placeholder="例：入力を自動化し、リアルタイムで在庫を把握したい">{{ old('improvement') }}</x-textarea>
    </x-form-field>

    {{-- チャット内容の添付（本人がチェックした場合のみ送信・保存） --}}
    <input type="hidden" name="chat_transcript" id="inquiry-chat-transcript" value="">
    <input type="hidden" name="conversation_id" id="inquiry-conversation-id" value="">
    <x-checkbox name="attach_chat" value="1" label="チャットでの相談内容を問い合わせに添付する" />

    {{-- 個人情報同意（必須） --}}
    <div>
        <x-checkbox name="consent" value="1">
            <span><a href="{{ route('privacy') }}" target="_blank" class="text-brand underline underline-offset-2">個人情報の取り扱い</a>に同意する<span class="text-red-500">*</span></span>
        </x-checkbox>
        <x-validation-error :messages="$errors->get('consent')" />
    </div>

    <div class="pt-1">
        <x-button type="submit" class="w-full sm:w-auto">送信する</x-button>
    </div>
</form>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&family=Dancing+Script:wght@700&display=swap');
    
    body {
        background-color: #f3e5f5; /* ライトラベンダー色の背景 */
        font-family: 'Noto Sans JP', sans-serif;
    }
    .main-content {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 2rem;
        min-height: 100vh;
    }
    .container {
        width: 100%;
        max-width: 600px;
        padding: 2rem;
        background-color: rgba(230, 230, 250, 0.85);
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    h2 {
        font-family: 'Dancing Script', cursive;
        font-size: 2.5rem;
        color: #8B7355;
        text-align: center;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(139,115,85,0.1);
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #8B7355;
        font-weight: bold;
    }
    input[type="password"] {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #D2B48C;
        border-radius: 5px;
        font-size: 1rem;
        background-color: #FFFFFF;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .btn-save {
        padding: 0.75rem 1.5rem;
        background-color: #D2B48C;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-save:hover {
        background-color: #8B4513;
    }
    .text-sm {
        font-size: 0.875rem;
    }
    .text-gray-600 {
        color: #718096;
    }
    .mt-1 {
        margin-top: 0.25rem;
    }
    .mt-2 {
        margin-top: 0.5rem;
    }
    .mt-6 {
        margin-top: 1.5rem;
    }
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
    .text-red-500 {
        color: #f56565;
    }
    .text-xs {
        font-size: 0.75rem;
    }
</style>

<div class="main-content">
    <div class="container">
        <h2>パスワードの更新</h2>
        <p class="text-sm text-gray-600 mt-1">
            アカウントのセキュリティを保つため、長くてランダムなパスワードを使用してください。
        </p>

        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="update_password_current_password">現在のパスワード</label>
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="update_password_password">新しいパスワード</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password">
                @error('password', 'updatePassword')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="update_password_password_confirmation">新しいパスワード（確認）</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="btn-save">保存</button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 mt-2"
                    >保存しました。</p>
                @endif
            </div>
        </form>
    </div>
</div>
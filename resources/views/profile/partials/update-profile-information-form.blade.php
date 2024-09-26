<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&family=Dancing+Script:wght@700&display=swap');
    body {
        font-family: 'Noto Sans JP', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background-image: url('{{ asset('images/plumeria_background.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    .header {
        background-color: #FFFFFF;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .header-content {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .header-link {
        color: #8B7355;
        text-decoration: none;
        margin-left: 1rem;
        font-weight: bold;
    }
    .header-link:hover {
        text-decoration: underline;
    }
    .main-content {
        flex-grow: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
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
    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #D2B48C;
        border-radius: 5px;
        font-size: 1rem;
        background-color: #FFFFFF;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    button {
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
    button:hover {
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
</style>

<body>
    <div class="main-content">
        <div class="container">
            <h2>プロフィール情報</h2>
            <p class="text-sm text-gray-600 mt-1">
                アカウントのプロフィール情報とメールアドレスを更新してください。
            </p>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('patch')
                <div class="form-group">
                    <label for="name">名前</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-gray-800">
                                メールアドレスが未確認です。
                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                                    確認メールを再送信するにはこちらをクリックしてください。
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    新しい確認リンクがメールアドレスに送信されました。
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <button type="submit">保存</button>

                    @if (session('status') === 'profile-updated')
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
</body>
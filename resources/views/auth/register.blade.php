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
        max-width: 500px;
        padding: 2rem;
        background-color: rgba(230, 230, 250, 0.85);
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    h1 {
        font-family: 'Dancing Script', cursive;
        font-size: 3rem;
        color: #8B7355;
        text-align: center;
        margin-bottom: 2rem;
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
    input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #D2B48C;
        border-radius: 5px;
        font-size: 1rem;
        background-color: #FFFFFF;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    button {
        width: 100%;
        padding: 1rem;
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
    .login-link {
        text-align: center;
        margin-top: 1rem;
    }
    .login-link a {
        color: #8B7355;
        text-decoration: none;
    }
    .login-link a:hover {
        text-decoration: underline;
    }
    .text-red-500 {
        color: #f56565;
    }
    .text-xs {
        font-size: 0.75rem;
    }
    .mt-1 {
        margin-top: 0.25rem;
    }
</style>

<body>
    <header class="header">
        <div class="header-content">
            <a href="{{ route('login') }}" class="header-link">ログイン</a>
            <a href="{{ route('register') }}" class="header-link">登録</a>
        </div>
    </header>

    <div class="main-content">
        <div class="container">
            <h1>Welcome to Hula Note</h1>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="name">名前</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">パスワード（確認）</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>
                <button type="submit">登録</button>
            </form>
            <div class="login-link">
                <a href="{{ route('login') }}">既に登録済みですか？</a>
            </div>
        </div>
    </div>
</body>
<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&family=Dancing+Script:wght@700&display=swap');
        .bg-plumeria {
            background-image: url('{{ asset('images/plumeria_background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .text-soft-brown { color: #8B7355; }
        .border-soft-brown { border-color: #8B7355; }
        .bg-soft-brown { background-color: #D2B48C; }
        .bg-lavender { background-color: rgba(230, 230, 250, 0.85); }
        body, h2, button, input { font-family: 'Noto Sans JP', sans-serif; }
        .title-font { font-family: 'Dancing Script', cursive; }
    </style>

    <div class="min-h-screen bg-plumeria flex justify-center items-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="bg-lavender rounded-lg shadow-lg p-8 mx-auto">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-soft-brown title-font inline-block shadow-lg" style="text-shadow: 2px 2px 4px rgba(139,115,85,0.1);">
                        {{ __('パスワードをリセット') }}
                    </h1>
                </div>

                <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 border border-soft-brown">
                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('パスワードをお忘れですか？ メールアドレスを入力してください。パスワードリセット用のリンクをお送りします。') }}
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('メールアドレス')" class="text-soft-brown" />
                            <x-text-input id="email" class="block mt-1 w-full border-soft-brown focus:border-soft-brown focus:ring focus:ring-soft-brown focus:ring-opacity-50" type="email" name="email" :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="bg-soft-brown hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                {{ __('パスワードリセットリンクを送信') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('login') }}" class="text-soft-brown hover:text-opacity-80 font-bold">
                        ← {{ __('ログインページに戻る') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
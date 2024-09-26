<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hula Note</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&family=Dancing+Script:wght@700&display=swap');
            
            body { 
                font-family: 'Noto Sans JP', sans-serif;
                margin: 0;
                padding: 0;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            .title-font { font-family: 'Dancing Script', cursive; }
            
            /* ヘッダースタイル */
            .header {
                background-color: #ffffff;
                border-bottom: 1px solid #e2e8f0;
                padding: 1rem;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
            }
            .header-container {
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .header-link {
                font-size: 1.4rem;
                font-weight: 600;
                color: #8B7355;
                text-decoration: none;
            }
            .header-link:hover {
                text-decoration: underline;
            }
            .header-buttons {
                display: flex;
                gap: 1rem;
            }
            .header-button {
                background-color: #D2B48C;
                color: #FFFFFF;
                border: none;
                padding: 0.5rem 1rem;
                border-radius: 9999px;
                font-weight: bold;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            .header-button:hover {
                background-color: #8B4513;
            }
            
            .bg-plumeria {
                background-image: url('{{ asset('images/plumeria_background.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                flex-grow: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                padding-top: 80px; /* ヘッダーの高さ分のパディングを追加 */
            }
            .text-soft-brown { color: #8B7355; }
            .border-soft-brown { border-color: #8B7355; }
            .bg-soft-brown { background-color: #D2B48C; }
            .bg-lavender { background-color: rgba(230, 230, 250, 0.95); }
            
            .main-content {
                width: 95%;
                max-width: 1000px;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                text-align: center;
            }
            
            .welcome-title {
                font-size: 2.5rem;
                font-weight: bold;
                color: #8B7355;
                text-shadow: 2px 2px 4px rgba(139,115,85,0.1);
                margin-bottom: 2rem;
                line-height: 1.2;
            }
            
            .feature-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
                margin-top: 2rem;
            }

            .feature-item {
                background-color: #FFFFFF;
                padding: 1.5rem;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s ease;
            }

            .feature-title {
                font-size: 1.5rem;
                color: #8B7355;
                margin-bottom: 0.5rem;
            }

            .feature-description {
                color: #666;
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <!-- ヘッダー -->
        <header class="header">
            <div class="header-container">
                <a href="{{ route('welcome') }}" class="header-link">Hula Note</a>
                <div class="header-buttons">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="header-button">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="header-button">ログイン</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="header-button">新規登録</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <div class="bg-plumeria">
            <div class="main-content bg-lavender">
                <h1 class="welcome-title title-font">
                    Welcome to " Hula Note "<br>
                    <span style="font-size: 0.7em;">（フラダンス振り付け習得アプリ）</span>
                </h1>

                <div class="feature-grid">
                    <div class="feature-item">
                        <h2 class="feature-title">振り付けを管理</h2>
                        <p class="feature-description">
                            フラダンスの振り付けを簡単に管理できます。動画をアップロードし、1秒ごとのキャプチャーで振り付けの流れを一目で確認できます。
                        </p>
                    </div>

                    <div class="feature-item">
                        <h2 class="feature-title">練習をサポート</h2>
                        <p class="feature-description">
                            キャプチャーした画像を使って、ステップなどの詳細な説明を追加できます。また、歌詞と合わせて振り付けを覚えることができ効率的な練習をサポートします。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
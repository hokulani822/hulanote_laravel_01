<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&family=Dancing+Script:wght@700&display=swap');

        .bg-plumeria {
            background-image: url('{{ asset('images/plumeria_background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .text-soft-brown {
            color: #8B7355;
        }
        .border-soft-brown {
            border-color: #8B7355;
        }
        .bg-soft-brown {
            background-color: #D2B48C;
        }
        .bg-lavender {
            background-color: rgba(230, 230, 250, 0.85);
        }
        body, h2, button, a {
            font-family: 'Noto Sans JP', sans-serif;
        }
        .title-font {
            font-family: 'Dancing Script', cursive;
        }
    </style>

    <div class="min-h-screen bg-plumeria flex justify-center items-center py-12 px-4">
        <div class="w-full max-w-4xl">
            <div class="bg-lavender rounded-lg shadow-lg p-8 mx-auto" style="width: 80%;">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-soft-brown title-font inline-block shadow-lg" style="text-shadow: 2px 2px 4px rgba(139,115,85,0.1);">
                        {{ $song->title }} の詳細
                    </h1>
                </div>

                <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 border border-soft-brown">
                    <h2 class="text-2xl font-semibold text-soft-brown mb-4">{{ $song->title }} - {{ $song->artist }}</h2>
                    
                    <div class="space-y-4">
                        <a href="{{ route('lyrics.edit', $song->id) }}" class="block w-full bg-soft-brown hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition duration-300 text-center shadow-md">
                            歌詞登録・和訳
                        </a>
                        
                        <a href="{{ route('choreography.show', $song->id) }}" class="block w-full bg-soft-brown hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition duration-300 text-center shadow-md">
                            振り付けを覚える
                        </a>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('songs.index') }}" class="text-soft-brown hover:text-opacity-80 font-bold">
                            ← 曲一覧に戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
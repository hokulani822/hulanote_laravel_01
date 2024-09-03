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
        body, h2, button, input {
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
                    <h1 class="text-5xl font-bold text-soft-brown title-font inline-block shadow-lg" style="text-shadow: 2px 2px 4px rgba(139,115,85,0.1);">
                        {{ $song->title }} の振り付け
                    </h1>
                </div>
                
                <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 border border-soft-brown">
                    @if($choreography)
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-soft-brown mb-2">説明</h2>
                            <p class="text-soft-brown">{{ $choreography->description ?? '説明がありません。' }}</p>
                        </div>
                        
                        @if($choreography->video_url)
                            <div class="mb-6">
                                <h2 class="text-2xl font-semibold text-soft-brown mb-2">振り付け動画</h2>
                                <div class="aspect-w-16 aspect-h-9">
                                    <iframe src="{{ $choreography->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                                </div>
                            </div>
                        @endif
                    @else
                        <p class="text-soft-brown mb-4">振り付け情報がまだ登録されていません。</p>
                    @endif

                    <div class="flex justify-between items-center mt-8">
                        <a href="{{ route('songs.show', $song) }}" class="text-soft-brown hover:text-opacity-80 font-bold">
                            ← 曲の詳細に戻る
                        </a>
                        <a href="{{ route('choreography.edit', $song) }}" class="bg-soft-brown hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition duration-300 shadow-md">
                            振り付け情報を編集
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
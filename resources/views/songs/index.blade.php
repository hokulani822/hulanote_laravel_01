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
        .song-title {
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .song-title:hover {
            color: #D2B48C;
        }
    </style>

    <div class="min-h-screen bg-plumeria flex justify-center items-center py-12 px-4">
        <div class="w-full max-w-4xl"> <!-- コンテナの最大幅を調整 -->
            <div class="bg-lavender rounded-lg shadow-lg p-8 mx-auto" style="width: 80%;"> <!-- ラベンダー部分の幅を80%に設定 -->
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-bold text-soft-brown title-font inline-block shadow-lg" style="text-shadow: 2px 2px 4px rgba(139,115,85,0.1);">
                        My Hula Note
                    </h1>
                </div>
                
                <div class="flex justify-end mb-6">
                    <button id="toggleFormBtn" class="bg-soft-brown hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition duration-300 text-base shadow-md">
                        新しい曲を追加
                    </button>
                </div>

                <!-- 曲の追加フォーム (最初は非表示) -->
                <div id="addSongForm" class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 mb-6 border border-soft-brown hidden">
                    <h2 class="text-2xl font-semibold text-soft-brown mb-4">新しい曲を追加</h2>
                    <form action="{{ route('songs.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="title" class="block text-base font-medium text-soft-brown">曲名</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-soft-brown shadow-sm focus:border-soft-brown focus:ring focus:ring-soft-brown focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="artist" class="block text-base font-medium text-soft-brown">アーティスト</label>
                            <input type="text" name="artist" id="artist" class="mt-1 block w-full rounded-md border-soft-brown shadow-sm focus:border-soft-brown focus:ring focus:ring-soft-brown focus:ring-opacity-50">
                        </div>
                        <button type="submit" class="w-full bg-soft-brown hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition duration-300 text-base shadow-md">
                            曲を追加
                        </button>
                    </form>
                </div>

                <!-- 曲のリスト -->
                <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 border border-soft-brown mx-auto">
                    <h2 class="text-3xl font-semibold text-soft-brown mb-4">My Hula Song</h2>
                    @if (count($songs) > 0)
                        <ul class="space-y-4">
                            @foreach ($songs as $song)
                                <li class="bg-lavender bg-opacity-50 p-4 rounded-lg shadow-md hover:shadow-lg transition duration-300 border border-soft-brown">
                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('songs.show', $song->id) }}" class="song-title text-lg font-medium text-soft-brown">
                                            {{ $song->title }} - {{ $song->artist }}
                                        </a>
                                        <div class="space-x-2">
                                            <a href="{{ route('songs.edit', $song->id) }}" class="text-soft-brown hover:text-opacity-80 font-bold text-sm">編集</a>
                                            <form action="{{ route('songs.destroy', $song->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-500 font-bold text-sm" onclick="return confirm('本当にこの曲を削除しますか？');">削除</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-soft-brown text-center text-lg">まだ曲が登録されていません。新しい曲を追加しましょう！</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const addSongForm = document.getElementById('addSongForm');

            toggleFormBtn.addEventListener('click', function() {
                addSongForm.classList.toggle('hidden');
            });
        });
    </script>
</x-app-layout>
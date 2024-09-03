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
                    <h1 class="text-4xl font-bold text-soft-brown title-font inline-block shadow-lg" style="text-shadow: 2px 2px 4px rgba(139,115,85,0.1);">
                        曲を編集
                    </h1>
                </div>

                <!-- バリデーションエラーの表示 -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- 編集フォーム -->
                <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 border border-soft-brown">
                   <form action="{{ route('songs.update', $song->id) }}" method="POST" class="space-y-4">
                    @csrf
                     @method('PUT')
                        
                        <div>
                            <label for="title" class="block text-base font-medium text-soft-brown">曲名</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $song->title) }}" class="mt-1 block w-full rounded-md border-soft-brown shadow-sm focus:border-soft-brown focus:ring focus:ring-soft-brown focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label for="artist" class="block text-base font-medium text-soft-brown">アーティスト</label>
                            <input type="text" name="artist" id="artist" value="{{ old('artist', $song->artist) }}" class="mt-1 block w-full rounded-md border-soft-brown shadow-sm focus:border-soft-brown focus:ring focus:ring-soft-brown focus:ring-opacity-50">
                        </div>
                        
                        <div class="flex justify-between items-center mt-6">
                            <a href="{{ route('songs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-full transition duration-300 text-base shadow-md">
                                キャンセル
                            </a>
                            <button type="submit" class="bg-soft-brown hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition duration-300 text-base shadow-md">
                                更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
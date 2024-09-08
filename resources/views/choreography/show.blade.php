<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&family=Dancing+Script:wght@700&display=swap');
        .bg-plumeria {
        background-image: url('{{ asset('images/plumeria_background.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed; /* この行を追加 */
        min-height: 100vh; /* この行を追加 */
        }
        .text-soft-brown { color: #8B7355; }
        .border-soft-brown { border-color: #8B7355; }
        .bg-soft-brown { background-color: #D2B48C; }
        .bg-lavender { background-color: rgba(230, 230, 250, 0.85); }
        body, h2, button, input { font-family: 'Noto Sans JP', sans-serif; }
        .title-font { font-family: 'Dancing Script', cursive; }
        
        /* フレームグリッドのスタイル */
        .frame-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }
        .frame-item {
            aspect-ratio: 16 / 9;
            overflow: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .frame-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
                        
                        @if($choreography && $choreography->videos->isNotEmpty())
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-soft-brown mb-2">振り付け動画</h2>
                            @foreach($choreography->videos as $video)
                                <div class="aspect-w-16 aspect-h-9 mb-4">
                                    <video controls class="w-full h-full">
                                        <source src="{{ secure_asset('storage/' . $video->url) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <p class="text-sm text-gray-600">アップロード日時: {{ $video->created_at }}</p>
                                <p class="text-sm text-gray-600">AI編集: {{ $video->ai_edited ? '完了' : '未処理' }}</p>
                            @endforeach
                        </div>
                    @endif

                        @if($choreography->frames)
                            <div class="mt-8">
                                <h2 class="text-2xl font-semibold text-soft-brown mb-4">振り付けフレーム</h2>
                                <div class="frame-grid">
                                    @foreach(json_decode($choreography->frames) as $frame)
                                        <div class="frame-item">
                                            <img src="{{ Storage::url(str_replace(storage_path('app/public'), '', $frame)) }}" alt="Frame">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <p class="text-soft-brown mb-4">振り付け情報がまだ登録されていません。</p>
                    @endif

                    <!-- 動画アップロードフォーム -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-soft-brown mb-2">新しい動画をアップロード</h2>
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ secure_url(route('choreography.upload_video', ['song' => $song])) }}" method="POST" enctype="multipart/form-data" id="videoUploadForm">
                            @csrf
                            <div class="mb-4">
                                <input type="file" name="video" accept="video/mp4,video/quicktime" class="mb-2" id="videoInput" required>
                                <p class="text-sm text-gray-600">許可される形式: MP4, MOV. 最大サイズ: 50MB</p>
                            </div>
                            <div class="mb-4 hidden" id="progressBarContainer">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-soft-brown h-2.5 rounded-full" id="progressBar" style="width: 0%"></div>
                                </div>
                                <p id="progressText" class="text-sm text-gray-600 mt-1">0%</p>
                            </div>
                            <button type="submit" class="bg-soft-brown hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition duration-300 shadow-md">
                                アップロード
                            </button>
                        </form>
                    </div>

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

    <script>
    document.getElementById('videoUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        var progressBarContainer = document.getElementById('progressBarContainer');
        var progressBar = document.getElementById('progressBar');
        var progressText = document.getElementById('progressText');

        xhr.open('POST', '/choreography/1/upload-video', true);
        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
                progressText.textContent = percentComplete.toFixed(2) + '%';
                progressBarContainer.classList.remove('hidden');
            }
        };
        xhr.onload = function() {
    console.log('Response status:', xhr.status);
    console.log('Response text:', xhr.responseText);
    try {
        var jsonEndIndex = xhr.responseText.indexOf('<!DOCTYPE html>');
        var jsonResponse = jsonEndIndex !== -1 ? xhr.responseText.substring(0, jsonEndIndex) : xhr.responseText;
        var response = JSON.parse(jsonResponse);
        if (xhr.status === 200 && response.success) {
            alert(response.message);
            // 動的にUIを更新
            var videoContainer = document.querySelector('.mb-6');
            if (videoContainer) {
                var newVideoItem = document.createElement('div');
                newVideoItem.className = 'aspect-w-16 aspect-h-9 mb-4';
                newVideoItem.innerHTML = `
                    <video controls class="w-full h-full">
                        <source src="${response.video_url}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <p class="text-sm text-gray-600">アップロード日時: たった今</p>
                    <p class="text-sm text-gray-600">AI編集: 未処理</p>
                `;
                videoContainer.insertBefore(newVideoItem, videoContainer.firstChild);
            } else {
                window.location.reload();
            }
        } else {
            alert(response.message || 'アップロードに失敗しました。もう一度お試しください。');
        }
    } catch (e) {
        console.error('Error parsing JSON:', e);
        console.error('Raw response:', xhr.responseText);
        alert('予期せぬエラーが発生しました。開発者ツールでエラー詳細を確認してください。');
    }
};
        xhr.onerror = function() {
            alert('ネットワークエラーが発生しました。接続を確認して再度お試しください。');
        };
        xhr.send(formData);
    });
    </script>
</x-app-layout>
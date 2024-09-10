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
        
        .main-content {
            width: 95%;
            max-width: 1200px;
        }
        .frame-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.75rem;
        }
        .frame-item {
            aspect-ratio: 9 / 16;
            overflow: hidden;
            border-radius: 0.25rem;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
        }
        .frame-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .video-wrapper {
        max-width: 640px; /* 動画の最大幅を設定 */
        margin: 0 auto; /* 中央寄せ */
    }
    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9のアスペクト比 */
        height: 0;
        overflow: hidden;
    }
    .video-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    </style>
  
    <div class="min-h-screen bg-plumeria flex justify-center items-center py-12 px-4">
        <div class="main-content">
            <div class="bg-lavender rounded-lg shadow-lg p-8 mx-auto">
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-bold text-soft-brown title-font inline-block shadow-lg" style="text-shadow: 2px 2px 4px rgba(139,115,85,0.1);">
                        {{ $song->title }} の振り付け
                    </h1>
                </div>
                
                <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-6 border border-soft-brown">
                @if($choreography && $choreography->videos->isNotEmpty())
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-soft-brown mb-2">振り付け動画</h2>
                    @foreach($choreography->videos as $video)
                        <div class="video-wrapper mb-4" data-video-id="{{ $video->id }}">
                            <div class="video-container">
                                <video controls>
                                    <source src="{{ secure_asset('storage/' . $video->url) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <p class="text-sm text-gray-600">アップロード日時: {{ $video->created_at }}</p>
                                    <p class="text-sm text-gray-600">AI編集: {{ $video->ai_edited ? '完了' : '処理中' }}</p>
                                </div>
                                <button class="delete-video bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" data-video-id="{{ $video->id }}">
                                    削除
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                        @if($choreography->frames)
                            <div class="mt-8">
                                <h2 class="text-2xl font-semibold text-soft-brown mb-4">振り付けフレーム</h2>
                                <div class="frame-grid">
                                    @foreach(json_decode($choreography->frames) as $frame)
                                        <div class="frame-item">
                                            <img src="{{ secure_asset('storage/' . $frame->frame_url) }}" alt="Frame at {{ $frame->timestamp }}s">
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

        xhr.open('POST', '{{ secure_url(route('choreography.upload_video', ['song' => $song])) }}', true);
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
                    window.location.reload();
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
    
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-video');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const videoId = this.getAttribute('data-video-id');
                if (confirm('この動画を削除してもよろしいですか？')) {
                    deleteVideo(videoId);
                }
            });
        });

        function deleteVideo(videoId) {
            fetch('{{ secure_url(route('choreography.delete_video', ['song' => $song, 'video' => ':videoId'])) }}'.replace(':videoId', videoId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const videoContainer = document.querySelector(`.video-container[data-video-id="${videoId}"]`);
                    videoContainer.remove();
                    alert(data.message);
                } else {
                    throw new Error(data.message || '動画の削除中にエラーが発生しました。');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(`動画の削除中にエラーが発生しました: ${error.message}`);
            });
        }
    });
    </script>
</x-app-layout>
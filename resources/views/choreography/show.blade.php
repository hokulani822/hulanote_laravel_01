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
        .frame-scroller {
            display: flex;
            overflow-x: auto;
            gap: 0.75rem;
            padding: 1rem 0;
            scroll-snap-type: x mandatory;
        }
        .frame-item {
            flex: 0 0 auto;
            width: 120px;
            aspect-ratio: 9 / 16;
            scroll-snap-align: start;
            overflow: hidden;
            border-radius: 0.25rem;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 5px solid transparent;
        }
        .frame-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .selectable .frame-item {
            transition: all 0.3s ease, opacity 0.3s ease;
        }
        .selectable .frame-item:hover {
            transform: scale(1.05);
        }
        .selectable .frame-item.selected {
            opacity: 0.5;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3); /* 暗い影を追加 */
        }
        
        .video-wrapper {
            max-width: 640px;
            margin: 0 auto;
        }
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
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
        
        .btn-action,
        .btn-delete,
        .btn-select-mode {
            background-color: #D2B48C;
            color: #FFFFFF;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .btn-action:hover,
        .btn-delete:hover,
        .btn-select-mode:hover {
            background-color: #8B4513;
            color: #FFFFFF;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        
        .btn-delete {
            background-color: #FF6347; /* トマト色 */
        }
        
        .btn-delete:hover {
            background-color: #D32F2F; /* 濃い赤 */
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
                                        <button class="delete-video btn-action btn-delete" data-video-id="{{ $video->id }}">
                                            動画を削除
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($choreography && $choreography->frames)
                            <div class="mt-8">
                                <h2 class="text-2xl font-semibold text-soft-brown mb-4">振り付け一覧</h2>
                                <button id="toggleSelectMode" class="btn-action btn-select-mode">
                                    不要な画像を選択する
                                </button>
                                
                                <div id="selectControls" class="mb-4 hidden">
                                    <button id="deleteUnselectedFrames" class="btn-action btn-delete">
                                        選択した画像を削除
                                    </button>
                                    <button id="undoDelete" class="btn-action ml-2 hidden">
                                        元に戻す
                                    </button>
                                </div>
                                <div id="frameScroller" class="frame-scroller">
                                    @foreach(json_decode($choreography->frames) as $index => $frame)
                                        <div class="frame-item" data-frame-index="{{ $index }}">
                                            <img src="{{ secure_asset('storage/' . $frame->frame_url) }}" alt="Frame at {{ $frame->timestamp }}s" class="frame-image">
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
                            <button type="submit" class="btn-action">
                                アップロード
                            </button>
                        </form>
                    </div>

                    <div class="flex justify-between items-center mt-8">
                        <a href="{{ route('songs.show', $song) }}" class="text-soft-brown hover:text-opacity-80 font-bold">
                            ← 曲の詳細に戻る
                        </a>
                        <!--<a href="{{ route('choreography.edit', $song) }}" class="btn-action">-->
                        <!--    振り付け情報を編集-->
                        <!--</a>-->
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleSelectModeButton = document.getElementById('toggleSelectMode');
    const selectControls = document.getElementById('selectControls');
    const deleteButton = document.getElementById('deleteUnselectedFrames');
    const undoButton = document.getElementById('undoDelete');
    const frameScroller = document.getElementById('frameScroller');
    let selectMode = false;
    let selectedFrames = [];
    let deletedFrames = [];

    if (toggleSelectModeButton && selectControls && deleteButton && undoButton && frameScroller) {
        toggleSelectModeButton.addEventListener('click', function() {
            selectMode = !selectMode;
            selectControls.classList.toggle('hidden');
            frameScroller.classList.toggle('selectable');
            toggleSelectModeButton.textContent = selectMode ? '選択モードを終了' : '不要な画像を選択する';
        
            if (!selectMode) {
                selectedFrames = [];
                document.querySelectorAll('.frame-item.selected').forEach(item => {
                    item.classList.remove('selected');
                });
            }
        });

        frameScroller.addEventListener('click', function(e) {
            if (!selectMode) return;
            
            const frameItem = e.target.closest('.frame-item');
            if (frameItem) {
                const frameIndex = parseInt(frameItem.dataset.frameIndex);
                if (frameItem.classList.toggle('selected')) {
                    selectedFrames.push(frameIndex);
                } else {
                    selectedFrames = selectedFrames.filter(index => index !== frameIndex);
                }
            }
        });

        deleteButton.addEventListener('click', function() {
            if (selectedFrames.length === 0) {
                alert('削除する画像が選択されていません。');
                return;
            }
        
            if (confirm(`選択された${selectedFrames.length}個の画像を削除してもよろしいですか？`)) {
                const songId = '{{ $song->id }}';
        
                fetch(`/choreography/${songId}/delete-frames`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ frames: selectedFrames })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        selectedFrames.forEach(index => {
                            const frameItem = document.querySelector(`.frame-item[data-frame-index="${index}"]`);
                            if (frameItem) {
                                deletedFrames.push({
                                    index: index,
                                    element: frameItem.cloneNode(true)
                                });
                                frameItem.remove();
                            }
                        });
                        selectedFrames = [];
                        alert(data.message);
                        undoButton.classList.remove('hidden');
                    } else {
                        alert('画像の削除中にエラーが発生しました: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('画像の削除中にエラーが発生しました。');
                });
            }
        });

        undoButton.addEventListener('click', function() {
            if (deletedFrames.length === 0) {
                alert('元に戻す操作はありません。');
                return;
            }

            const songId = '{{ $song->id }}';
            const framesToRestore = deletedFrames.map(frame => frame.index);

            console.log('Restoring frames:', framesToRestore);

            fetch(`/choreography/${songId}/restore-frames`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ frames: framesToRestore })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data);
                if (data.success) {
                    deletedFrames.forEach(frame => {
                        const existingFrame = document.querySelector(`.frame-item[data-frame-index="${frame.index}"]`);
                        if (!existingFrame) {
                            frameScroller.appendChild(frame.element);
                        } else {
                            existingFrame.classList.remove('deleted');
                        }
                    });
                    deletedFrames = [];
                    undoButton.classList.add('hidden');
                    alert(data.message);
                } else {
                    alert('画像の復元中にエラーが発生しました: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('画像の復元中にエラーが発生しました。');
            });
        });
    }
    
     // 動画削除機能を追加
    document.querySelectorAll('.delete-video').forEach(button => {
        button.addEventListener('click', function() {
            const videoId = this.dataset.videoId;
            const songId = '{{ $song->id }}';
            if (confirm('この動画を削除してもよろしいですか？')) {
                fetch(`/choreography/${songId}/video/${videoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // 動画要素を画面から削除
                        this.closest('.video-wrapper').remove();
                    } else {
                        alert('動画の削除中にエラーが発生しました: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('動画の削除中にエラーが発生しました。');
                });
            }
        });
    });
    
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
});
</script>
</x-app-layout>
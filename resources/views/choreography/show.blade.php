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
            height: 300px; /* 適切な高さに調整してください */
        }
        
        .frame-item {
            flex: 0 0 auto;
            width: 120px;
            max-height: 280px; /* frame-scrollerの高さより少し小さく */
            overflow-y: auto; /* 縦方向にスクロール可能に */
            scroll-snap-align: start;
            margin-bottom: 10px;
            border-radius: 0.25rem;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
        }
        
        .frame-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
       .frame-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 120px;
            height: auto; /* 高さを自動調整 */
            margin-bottom: 10px;
        }
        
       .frame-image {
            width: 100%;
            height: auto;
            margin-bottom: 5px;
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
        
        .step-info {
            width: 100%;
            text-align: center;
            padding: 5px;
            background-color: rgba(210, 180, 140, 0.1);
            border: 1px solid #8B7355;
            border-radius: 4px;
            font-size: 12px;
            color: #8B7355;
            min-height: 25px;
            overflow-wrap: break-word; /* 長い単語を折り返す */
        }
        
        #stepSelector select, #stepSelector button {
            font-size: 0.9rem;
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
                    @if(!$hasVideo)
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
                                    <p class="text-sm text-gray-600">許可される形式: MP4, MOV. 最大サイズ: 100MB</p>
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
                    @else
                        <!-- 動画が存在する場合の表示 -->
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
                                            </div>
                                            <button class="delete-video btn-action btn-delete" data-video-id="{{ $video->id }}">
                                                動画を削除
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- 歌詞入力フォーム -->
                        @if($choreography && $choreography->frames)
                               <div class="mb-4">
                                <h2 class="text-2xl font-semibold text-soft-brown mb-4">歌詞</h2>
                                <div id="lyricsContainer" class="relative">
                                <div id="lyricsFrame" class="w-full p-2 border border-soft-brown rounded bg-white bg-opacity-75 whitespace-nowrap overflow-x-auto" contenteditable="true">
                                    {{ $choreography->lyrics_frames ?? '' }}
                                </div>
                                <p id="saveStatus" class="text-sm text-gray-600 mt-2"></p>
                            </div>
                            </div>
                                
                                <div id="frameScroller" class="frame-scroller">
                                @foreach(json_decode($choreography->frames) as $index => $frame)
                                    <div class="frame-item" data-frame-index="{{ $index }}">
                                    <img src="{{ secure_asset('storage/' . $frame->frame_url) }}" alt="Frame at {{ $frame->timestamp }}s" class="frame-image">
                                    <div class="step-info" id="step-{{ $index }}">
                                        {{ $choreography->steps[$index] ?? '' }}
                                    </div>
                                </div>
                                 @endforeach
                            </div>
                            <div id="stepSelector" class="hidden mt-4">
                                <select id="stepOptions" class="mr-2 p-2 border rounded">
                                    <option value="">ステップを選択</option>
                                    <option value="カホロ">カホロ</option>
                                    <option value="ヘラ">ヘラ</option>
                                    <option value="カオ">カオ</option>
                                    <option value="アミ">アミ</option>
                                    <option value="ウエへ">ウエへ</option>
                                    <option value="レレウエへ">レレウエへ</option>
                                    <option value="カラカウア">カラカウア</option>
                                    <!-- 他のステップオプションを追加 -->
                                    <option value="その他">その他</option>
                                </select>
                                <input type="text" id="customStep" class="mr-2 p-2 border rounded hidden" placeholder="ステップを入力">
                                <button id="applyStep" class="btn-action">適用</button>
                            </div>
                                
                                <div class="flex justify-end mb-4">
                                    <button id="toggleSelectMode" class="btn-action btn-select-mode">
                                        不要な画像を削除
                                    </button>
                                    <button id="toggleStepMode" class="btn-action btn-select-mode">
                                        ステップを追加
                                    </button>
                                </div>
                                
                                <div id="selectControls" class="flex justify-end mb-4 hidden">
                                    <button id="deleteUnselectedFrames" class="btn-action btn-delete">
                                        選択した画像を削除
                                    </button>
                            </div>
                            </div>
                         @endif
                    @endif


                    <div class="flex justify-between items-center mt-8">
                        <a href="{{ route('songs.show', $song) }}" class="text-soft-brown hover:text-opacity-80 font-bold">
                            ← 曲の詳細に戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleSelectModeButton = document.getElementById('toggleSelectMode');
    const toggleStepModeButton = document.getElementById('toggleStepMode');
    const selectControls = document.getElementById('selectControls');
    const deleteButton = document.getElementById('deleteUnselectedFrames');
    const frameScroller = document.getElementById('frameScroller');
    const lyricsFrame = document.getElementById('lyricsFrame');
    const saveStatus = document.getElementById('saveStatus');
    const stepSelector = document.getElementById('stepSelector');
    const stepOptions = document.getElementById('stepOptions');
    const customStepInput = document.getElementById('customStep');
    const applyStepButton = document.getElementById('applyStep');
    let selectMode = false;
    let stepMode = false;
    let selectedFrames = [];
    let selectedStepFrames = [];
    let timeoutId;

    if (toggleSelectModeButton && selectControls && deleteButton && frameScroller) {
        toggleSelectModeButton.addEventListener('click', function() {
            selectMode = !selectMode;
            stepMode = false;
            selectControls.classList.toggle('hidden');
            frameScroller.classList.toggle('selectable');
            toggleSelectModeButton.textContent = selectMode ? '削除モードを終了' : '不要な画像を削除';
            stepSelector.classList.add('hidden');
            toggleStepModeButton.textContent = 'ステップを追加';
            
            console.log('削除モード:', selectMode ? 'オン' : 'オフ');

            if (!selectMode) {
                selectedFrames = [];
                document.querySelectorAll('.frame-item.selected').forEach(item => {
                    item.classList.remove('selected');
                });
            }
        });

        toggleStepModeButton.addEventListener('click', function() {
            stepMode = !stepMode;
            selectMode = false;
            stepSelector.classList.toggle('hidden');
            frameScroller.classList.toggle('selectable');
            toggleStepModeButton.textContent = stepMode ? 'ステップ追加モードを終了' : 'ステップを追加';
            selectControls.classList.add('hidden');
            toggleSelectModeButton.textContent = '不要な画像を削除';

            console.log('ステップモード:', stepMode ? 'オン' : 'オフ');

            if (!stepMode) {
                selectedStepFrames.forEach(frame => frame.classList.remove('selected'));
                selectedStepFrames = [];
            }
        });

        frameScroller.addEventListener('click', function(e) {
            const frameItem = e.target.closest('.frame-item');
            if (!frameItem) return;

            if (selectMode) {
                const frameIndex = parseInt(frameItem.dataset.frameIndex);
                if (frameItem.classList.toggle('selected')) {
                    selectedFrames.push(frameIndex);
                } else {
                    selectedFrames = selectedFrames.filter(index => index !== frameIndex);
                }
                console.log('選択されたフレーム:', selectedFrames);
            } else if (stepMode) {
                frameItem.classList.toggle('selected');
                if (frameItem.classList.contains('selected')) {
                    selectedStepFrames.push(frameItem);
                } else {
                    selectedStepFrames = selectedStepFrames.filter(frame => frame !== frameItem);
                }
                console.log('ステップ用に選択されたフレーム:', selectedStepFrames.map(frame => frame.dataset.frameIndex));
            }
        });

        deleteButton.addEventListener('click', function() {
            if (selectedFrames.length === 0) {
                alert('削除する画像が選択されていません。');
                return;
            }
        
            if (confirm(`選択された${selectedFrames.length}個の画像を削除してもよろしいですか？削除した画像は復元できません`)) {
                const songId = '{{ $song->id }}';
        
                fetch(`/choreography/${songId}/delete-frames`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
                                frameItem.remove();
                            }
                        });
                        selectedFrames = [];
                        alert(data.message);
                        updateFrameIndices();
                    } else {
                        alert('画像の削除中にエラーが発生しました: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('エラー:', error);
                    alert('画像の削除中にエラーが発生しました。');
                });
            }
        });
    }
    
    if (lyricsFrame && frameScroller) {
        frameScroller.addEventListener('scroll', function() {
            lyricsFrame.scrollLeft = frameScroller.scrollLeft;
        });

        lyricsFrame.addEventListener('scroll', function() {
            frameScroller.scrollLeft = lyricsFrame.scrollLeft;
        });

        lyricsFrame.addEventListener('input', function() {
            clearTimeout(timeoutId);
            saveStatus.textContent = '保存中...';
            
            timeoutId = setTimeout(function() {
                saveLyrics();
            }, 1000);
        });
    }

    function saveLyrics() {
        const songId = '{{ $song->id }}';
        const lyrics = lyricsFrame.textContent;

        fetch(`/choreography/${songId}/update-lyrics`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ lyrics: lyrics })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                saveStatus.textContent = '保存しました';
                setTimeout(() => {
                    saveStatus.textContent = '';
                }, 2000);
            } else {
                saveStatus.textContent = '保存に失敗しました';
            }
        })
        .catch(error => {
            console.error('エラー:', error);
            saveStatus.textContent = '保存に失敗しました';
        });
    }

    function updateFrameIndices() {
        document.querySelectorAll('.frame-item').forEach((item, index) => {
            item.dataset.frameIndex = index;
        });
    }

    stepOptions.addEventListener('change', function() {
        if (this.value === 'その他') {
            customStepInput.classList.remove('hidden');
        } else {
            customStepInput.classList.add('hidden');
        }
    });

    applyStepButton.addEventListener('click', function() {
        console.log('適用ボタンがクリックされました');
        if (selectedStepFrames.length === 0) {
            console.log('フレームが選択されていません');
            return;
        }
        if (!stepOptions.value) {
            console.log('ステップが選択されていません');
            return;
        }
        
        let step = stepOptions.value;
        if (step === 'その他') {
            step = customStepInput.value;
            if (!step) {
                console.log('カスタムステップが入力されていません');
                return;
            }
        }
        
        console.log('選択されたフレーム:', selectedStepFrames.map(frame => frame.dataset.frameIndex));
        console.log('選択されたステップ:', step);
        
        // UIを更新
        selectedStepFrames.forEach(frame => {
            const stepInfo = frame.querySelector('.step-info');
            if (stepInfo) {
                stepInfo.textContent = step;
            }
        });
        
        // サーバーにデータを送信
        updateSteps(selectedStepFrames.map(frame => frame.dataset.frameIndex), step);
        
        // 選択をリセット
        selectedStepFrames.forEach(frame => frame.classList.remove('selected'));
        selectedStepFrames = [];
        stepOptions.value = '';
        customStepInput.value = '';
        customStepInput.classList.add('hidden');
    });

    function updateSteps(frameIndices, step) {
        const songId = '{{ $song->id }}';
        console.log('updateSteps が呼び出されました:', frameIndices, step);
        fetch(`/choreography/${songId}/update-steps`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ frameIndices, step })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('ステップが正常に更新されました:', data);
            } else {
                console.error('ステップの更新に失敗しました:', data);
            }
        })
        .catch(error => {
            console.error('エラー:', error);
        });
    }

    // 動画アップロード関連のコード（既存のコード）
    const videoUploadForm = document.getElementById('videoUploadForm');
    if (videoUploadForm) {
        videoUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            var progressBarContainer = document.getElementById('progressBarContainer');
            var progressBar = document.getElementById('progressBar');
            var progressText = document.getElementById('progressText');

            xhr.open('POST', this.action, true);
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    var percentComplete = (e.loaded / e.total) * 100;
                    progressBar.style.width = percentComplete + '%';
                    progressText.textContent = percentComplete.toFixed(2) + '%';
                    progressBarContainer.classList.remove('hidden');
                }
            };
            xhr.onload = function() {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200 && response.success) {
                        alert(response.message);
                        window.location.reload();
                    } else {
                        alert(response.message || 'アップロードに失敗しました。もう一度お試しください。');
                    }
                } catch (e) {
                    console.error('JSONの解析エラー:', e);
                    alert('予期せぬエラーが発生しました。開発者ツールでエラー詳細を確認してください。');
                }
            };
            xhr.onerror = function() {
                alert('ネットワークエラーが発生しました。接続を確認して再度お試しください。');
            };
            xhr.send(formData);
        });
    }

    // 動画削除関連のコード（既存のコード）
    document.querySelectorAll('.delete-video').forEach(button => {
        button.addEventListener('click', function() {
            const videoId = this.dataset.videoId;
            const songId = '{{ $song->id }}';
            if (confirm('この動画を削除してもよろしいですか？')) {
                fetch(`/choreography/${songId}/video/${videoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        this.closest('.video-wrapper').remove();
                    } else {
                        alert('動画の削除中にエラーが発生しました: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('エラー:', error);
                    alert('動画の削除中にエラーが発生しました。');
                });
            }
        });
    });
});
</script>
</x-app-layout>
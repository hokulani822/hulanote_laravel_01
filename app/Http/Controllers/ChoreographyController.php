<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Choreography;
use App\Models\ChoreographyVideo;
use App\Services\VideoProcessingService;
use App\Services\FrameExtractionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class ChoreographyController extends Controller
{
    protected $videoProcessingService;
    protected $frameExtractionService;

    public function __construct(VideoProcessingService $videoProcessingService, FrameExtractionService $frameExtractionService)
    {
        $this->videoProcessingService = $videoProcessingService;
        $this->frameExtractionService = $frameExtractionService;
    }

    public function show(Song $song)
    {
    $choreography = $song->choreography()->with('videos')->first();
    $hasVideo = $choreography && $choreography->videos->isNotEmpty();
    return view('choreography.show', compact('song', 'choreography', 'hasVideo'));
    }

    public function uploadVideo(Request $request, Song $song)
    {
    Log::info('Video upload process started', ['song_id' => $song->id]);

    try {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/quicktime|max:150000',
        ]);

        if ($request->hasFile('video')) {
            $choreography = $song->choreography ?? $song->choreography()->create();
            
            // 既存の動画を削除
            if ($choreography->videos()->exists()) {
                foreach ($choreography->videos as $oldVideo) {
                    Storage::delete('public/' . $oldVideo->url);
                    $oldVideo->delete();
                }
                // フレームもクリア
                $choreography->update(['frames' => null]);
            }

            $path = $request->file('video')->store('videos', 'public');
            $fullPath = storage_path('app/public/' . $path);
            Log::info('Video file stored', ['path' => $fullPath]);

            $video = $choreography->videos()->create([
                'url' => $path,
                'ai_edited' => false,
            ]);
            Log::info('Video record created', ['video_id' => $video->id]);

            // フレーム抽出を非同期で実行
            Artisan::queue('app:extract-video-frames', ['video_id' => $video->id]);

            return response()->json([
                'success' => true,
                'message' => '動画が更新されました。画像を抽出します。',
                'video_url' => Storage::url($path),
                'video_id' => $video->id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '動画のアップロードに失敗しました。'
        ], 400);

    } catch (\Exception $e) {
        Log::error('Error in upload video', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => '動画のアップロードに失敗しました: ' . $e->getMessage()
        ], 500);
    }
    }

    public function deleteVideo(Song $song, ChoreographyVideo $video)
    {
        try {
            Log::info('Video deletion process started', ['video_id' => $video->id]);

            $choreography = $video->choreography;

            // フレーム画像の削除
            if ($choreography->frames) {
                $frames = json_decode($choreography->frames, true);
                foreach ($frames as $frame) {
                    Storage::delete('public/' . $frame['frame_url']);
                }
            }

            // 動画ファイルの削除
            Storage::delete('public/' . $video->url);

            // データベースから動画レコードを削除
            $video->delete();

            // Choreographyのframesをクリア
            $choreography->update(['frames' => null]);

            Log::info('Video and associated frames deleted successfully', ['video_id' => $video->id]);

            return response()->json([
                'success' => true,
                'message' => '動画が正常に削除されました。'
            ]);
        } catch (\Exception $e) {
            Log::error('Video deletion error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => '動画の削除中にエラーが発生しました。'
            ], 500);
        }
    }

    private function getTimestampFromFramePath($framePath)
    {
        // フレームのファイル名から時間を抽出するロジックを実装
        // 例: frame001.jpg から 1 を抽出し、フレームレートを考慮して秒数に変換
        $filename = basename($framePath);
        preg_match('/frame(\d+)\.jpg/', $filename, $matches);
        $frameNumber = isset($matches[1]) ? intval($matches[1]) : 0;
        $frameRate = 1; // 1秒ごとにフレームを抽出した場合
        return $frameNumber * $frameRate;
    }
    
    public function deleteFrames(Request $request, Song $song)
{
    $choreography = $song->choreography;
    if (!$choreography) {
        return response()->json(['success' => false, 'message' => '振り付けが見つかりません。'], 404);
    }

    $framesToDelete = $request->input('frames');
    $frames = json_decode($choreography->frames, true);

    $deletedCount = 0;
    foreach ($framesToDelete as $index) {
        if (isset($frames[$index])) {
            unset($frames[$index]);
            $deletedCount++;
        }
    }

    if ($deletedCount > 0) {
        $choreography->frames = json_encode(array_values($frames));
        $choreography->save();
        return response()->json([
            'success' => true,
            'message' => $deletedCount . '個の画像が削除されました。'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => '削除する画像がありませんでした。'
    ]);
}

public function updateLyrics(Request $request, Song $song)
{
    $choreography = $song->choreography;
    if (!$choreography) {
        return response()->json(['success' => false, 'message' => '振り付けが見つかりません。'], 404);
    }

    $lyrics = $request->input('lyrics');
    $choreography->lyrics_frames = $lyrics;
    $choreography->save();

    return response()->json([
        'success' => true,
        'message' => '歌詞が更新されました。'
    ]);
}

public function updateSteps(Request $request, Song $song)
{
    $choreography = $song->choreography;
    if (!$choreography) {
        return response()->json(['success' => false, 'message' => '振り付けが見つかりません。'], 404);
    }

    $frameIndices = $request->input('frameIndices');
    $step = $request->input('step');

    $steps = $choreography->steps ?? [];
    foreach ($frameIndices as $index) {
        $steps[$index] = $step;
    }

    $choreography->steps = $steps;
    $choreography->save();

    return response()->json([
        'success' => true,
        'message' => 'ステップが更新されました。'
    ]);
}

}
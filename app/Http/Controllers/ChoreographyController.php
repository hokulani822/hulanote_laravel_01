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
        return view('choreography.show', compact('song', 'choreography'));
    }

    public function uploadVideo(Request $request, Song $song)
    {
        Log::info('Video upload process started', ['song_id' => $song->id]);

        try {
            $request->validate([
                'video' => 'required|file|mimetypes:video/mp4,video/quicktime|max:50000',
            ]);

            if ($request->hasFile('video')) {
                $path = $request->file('video')->store('videos', 'public');
                $fullPath = storage_path('app/public/' . $path);
                Log::info('Video file stored', ['path' => $fullPath]);

                $choreography = $song->choreography ?? $song->choreography()->create();
                
                $video = $choreography->videos()->create([
                    'url' => $path,
                    'ai_edited' => false,
                ]);
                Log::info('Video record created', ['video_id' => $video->id]);

                // フレーム抽出を非同期で実行
                Artisan::queue('app:extract-video-frames', ['video_id' => $video->id]);

                return response()->json([
                    'success' => true,
                    'message' => '動画がアップロードされました。フレーム抽出は背景で実行されます。',
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
}
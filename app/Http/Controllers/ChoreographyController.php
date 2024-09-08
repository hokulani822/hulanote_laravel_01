<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Choreography;
use App\Services\VideoProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ChoreographyController extends Controller
{
    protected $videoProcessingService;

    public function __construct(VideoProcessingService $videoProcessingService)
    {
        $this->videoProcessingService = $videoProcessingService;
    }

    public function show(Song $song)
    {
    $choreography = $song->choreography()->with('videos')->first();
    return view('choreography.show', compact('song', 'choreography'));
    }

    public function uploadVideo(Request $request, Song $song)
{
    try {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/quicktime|max:50000',
        ]);

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('videos', 'public');
            
            $choreography = $song->choreography ?? $song->choreography()->create();
            
            $video = $choreography->videos()->create([
                'url' => $path,
                'ai_edited' => false, // AIの処理は行わないのでfalseに設定
            ]);

            return response()->json([
                'success' => true,
                'message' => '動画がアップロードされました。',
                'video_url' => Storage::url($path),
                'video_id' => $video->id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '動画のアップロードに失敗しました。'
        ], 400);
    } catch (\Exception $e) {
        \Log::error('Error in upload video: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => '動画のアップロードに失敗しました: ' . $e->getMessage()
        ], 500);
    }
}
}
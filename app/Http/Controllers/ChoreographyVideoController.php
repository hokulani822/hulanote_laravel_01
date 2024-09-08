<?php

namespace App\Http\Controllers;

use App\Models\ChoreographyVideo;
use App\Models\Choreography;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChoreographyVideoController extends Controller
{
    public function showUploadForm()
    {
        $choreographies = Choreography::all();
        return view('choreography_videos.upload', compact('choreographies'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'video' => 'required|file|mimetypes:video/mp4,video/quicktime|max:50000',
                'choreography_id' => 'required|exists:choreographies,id'
            ]);

            $path = $request->file('video')->store('videos', 'public');
            
            $video = ChoreographyVideo::create([
                'choreography_id' => $validatedData['choreography_id'],
                'url' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => '動画がアップロードされました。',
                'video_id' => $video->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error in upload video: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '動画のアップロードに失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(ChoreographyVideo $video)
    {
        return view('choreography_videos.show', compact('video'));
    }
}
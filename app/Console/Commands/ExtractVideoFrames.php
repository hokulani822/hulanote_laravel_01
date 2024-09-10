<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChoreographyVideo;
use App\Services\FrameExtractionService;
use Illuminate\Support\Facades\Log;

class ExtractVideoFrames extends Command
{
    protected $signature = 'app:extract-video-frames {video_id?}';
    protected $description = 'Extract frames from choreography videos';

    private $frameExtractionService;

    public function __construct(FrameExtractionService $frameExtractionService)
    {
        parent::__construct();
        $this->frameExtractionService = $frameExtractionService;
    }

    public function handle()
{
    $videoId = $this->argument('video_id');

    $this->info("Attempting to process video with ID: {$videoId}");

    $video = \App\Models\ChoreographyVideo::find($videoId);

    if (!$video) {
        $this->error("Video with ID {$videoId} not found.");
        return;
    }

    $this->info("Video found. URL: {$video->url}");
    $fullPath = storage_path('app/public/' . $video->url);
    $this->info("Full path: " . $fullPath);
    $this->info("File exists: " . (file_exists($fullPath) ? 'Yes' : 'No'));

    try {
        $frameData = $this->frameExtractionService->extractFrames($video);
        $video->choreography->update(['frames' => json_encode($frameData)]);
        $this->info("Frames extracted successfully. Total frames: " . count($frameData));
    } catch (\Exception $e) {
        $this->error("Frame extraction failed: " . $e->getMessage());
        \Log::error("Frame extraction failed for video ID {$videoId}: " . $e->getMessage());
    }
}

    private function processVideo($videoId)
    {
        $video = ChoreographyVideo::find($videoId);

        if (!$video) {
            $this->error("Video with ID {$videoId} not found.");
            return;
        }

        $this->info("Extracting frames for video ID: {$videoId}");

        try {
            $frameData = $this->frameExtractionService->extractFrames($video);
            $video->choreography->update(['frames' => json_encode($frameData)]);
            $this->info("Frames extracted successfully. Total frames: " . count($frameData));
        } catch (\Exception $e) {
            $this->error("Frame extraction failed: " . $e->getMessage());
            Log::error("Frame extraction failed for video ID {$videoId}: " . $e->getMessage());
        }
    }

    private function processAllVideos()
    {
        $videos = ChoreographyVideo::all();

        $this->info("Processing all videos. Total videos: " . $videos->count());

        foreach ($videos as $video) {
            $this->processVideo($video->id);
        }

        $this->info("All videos processed.");
    }
}
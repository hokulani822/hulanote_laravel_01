<?php

namespace App\Services;
use App\Models\ChoreographyVideo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FrameExtractionService
{
    public function extractFrames(ChoreographyVideo $video)
{
    $inputPath = storage_path('app/public/' . $video->url);
    $outputPath = storage_path('app/public/frames/' . $video->id);

    if (!file_exists($inputPath)) {
        throw new \Exception("Input file not found: " . $inputPath);
    }
    
    if (!is_dir($outputPath)) {
        mkdir($outputPath, 0755, true);
    }

    // フレーム抽出コマンドを構築
     $command = "ffmpeg -i \"{$inputPath}\" -vf \"fps=1,scale=240:-1,crop=240:427\" \"{$outputPath}/frame%03d.jpg\"";
    
    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        throw new \Exception('Frame extraction failed: ' . implode("\n", $output));
    }

    $frames = glob($outputPath . '/*.jpg');
    $frameData = [];
    foreach ($frames as $index => $framePath) {
        $frameData[] = [
            'frame_url' => 'frames/' . $video->id . '/' . basename($framePath),
            'timestamp' => $index + 1
        ];
    }

    return $frameData;
}
}
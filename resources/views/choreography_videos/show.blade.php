<!-- resources/views/choreography_videos/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-semibold mb-4">振付動画</h1>
    
    <div class="mb-4">
        <h2 class="text-xl font-semibold mb-2">動画</h2>
        <video controls class="w-full">
            <source src="{{ Storage::url($video->url) }}" type="video/mp4">
            お使いのブラウザは動画タグをサポートしていません。
        </video>
    </div>

    <div class="mt-4">
        <a href="{{ route('choreography_videos.upload.form') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">新しい動画をアップロード</a>
    </div>
</div>
@endsection
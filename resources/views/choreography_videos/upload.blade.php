<!-- resources/views/choreography_videos/upload.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-2xl font-semibold mb-4">振付動画アップロード</h2>
    <form action="{{ route('choreography_videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="video" class="block text-sm font-medium text-gray-700">動画ファイル:</label>
            <input type="file" name="video" id="video" accept="video/*" required class="mt-1 block w-full">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">アップロード</button>
    </form>
</div>
@endsection
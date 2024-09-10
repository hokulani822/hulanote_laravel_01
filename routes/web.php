<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\ChoreographyController;
use App\Http\Controllers\ChoreographyVideoController;
use App\Http\Controllers\LyricsController;
use Illuminate\Support\Facades\Route;

// 曲関連のルート
Route::get('/', [SongController::class, 'index'])->name('songs.index');
Route::get('/dashboard', [SongController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::post('/songs', [SongController::class, 'store'])->name('songs.store');
Route::delete('/songs/{song}', [SongController::class, 'destroy'])->name('songs.destroy');
Route::get('/songs/{song}', [SongController::class, 'show'])->name('songs.show');
Route::get('/songs/{song}/edit', [SongController::class, 'edit'])->name('songs.edit');
Route::put('/songs/{song}', [SongController::class, 'update'])->name('songs.update');

// 歌詞関連のルート
Route::get('/lyrics/{song}/edit', [LyricsController::class, 'edit'])->name('lyrics.edit');

// 振り付け関連のルート
Route::prefix('choreography')->group(function () {
    Route::get('/{song}', [ChoreographyController::class, 'show'])->name('choreography.show');
    Route::get('/{song}/edit', [ChoreographyController::class, 'edit'])->name('choreography.edit');
    Route::put('/{song}', [ChoreographyController::class, 'update'])->name('choreography.update');
    Route::post('/{song}/upload-video', [ChoreographyController::class, 'uploadVideo'])
        ->name('choreography.upload_video')
        ->middleware('auth')  // 認証済みユーザーのみアクセス可能
        ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);  // CSRFトークン検証を無効化（必要に応じて）
    Route::delete('/choreography/{song}/video/{video}', [ChoreographyController::class, 'deleteVideo'])
        ->name('choreography.delete_video');
});

// 振り付け動画関連のルート
Route::prefix('choreography-videos')->group(function () {
    Route::get('/upload', [ChoreographyVideoController::class, 'showUploadForm'])->name('choreography_videos.upload.form');
    Route::post('/upload', [ChoreographyVideoController::class, 'store'])->name('choreography_videos.store');
    Route::get('/{video}', [ChoreographyVideoController::class, 'show'])->name('choreography_videos.show');
});

// プロフィール関連のルート
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
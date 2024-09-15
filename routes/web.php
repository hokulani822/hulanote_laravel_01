<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\ChoreographyController;
use App\Http\Controllers\ChoreographyVideoController;
use App\Http\Controllers\LyricsController;
use Illuminate\Support\Facades\Route;

// 公開ルート
Route::get('/', [SongController::class, 'index'])->name('songs.index');
Route::get('/songs/{song}', [SongController::class, 'show'])->name('songs.show');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // ダッシュボード
    Route::get('/dashboard', [SongController::class, 'index'])->name('dashboard');

    // 曲関連のルート
    Route::post('/songs', [SongController::class, 'store'])->name('songs.store');
    Route::delete('/songs/{song}', [SongController::class, 'destroy'])->name('songs.destroy');
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
            ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        Route::delete('/{song}/video/{video}', [ChoreographyController::class, 'deleteVideo'])
            ->name('choreography.delete_video');
        Route::delete('/{song}/delete-frames', [ChoreographyController::class, 'deleteFrames'])
            ->name('choreography.delete_frames');
        Route::post('/{song}/restore-frames', [ChoreographyController::class, 'restoreFrames'])
            ->name('choreography.restore_frames');
    });

    // 振り付け動画関連のルート
    Route::prefix('choreography-videos')->group(function () {
        Route::get('/upload', [ChoreographyVideoController::class, 'showUploadForm'])->name('choreography_videos.upload.form');
        Route::post('/upload', [ChoreographyVideoController::class, 'store'])->name('choreography_videos.store');
        Route::get('/{video}', [ChoreographyVideoController::class, 'show'])->name('choreography_videos.show');
        Route::delete('/choreography/{song}/video/{video}', [ChoreographyController::class, 'deleteVideo'])->name('choreography.delete_video');
    });

    // プロフィール関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
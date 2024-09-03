<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; //Add
use App\Http\Controllers\SongController; //Add
use App\Http\Controllers\ChoreographyController;//Add

//曲：ダッシュボード表示(songs.blade.php)
Route::get('/', [SongController::class, 'index'])->name('songs.index');
Route::get('/dashboard', [SongController::class, 'index'])->middleware(['auth'])->name('dashboard');

//曲：追加 
Route::post('/songs', [SongController::class, 'store'])->name('songs.store');

//曲：削除 
Route::delete('/songs/{song}', [SongController::class, 'destroy'])->name('songs.destroy');

//曲：詳細
Route::get('/songs/{song}', [SongController::class, 'show'])->name('songs.show');

//曲：更新画面
Route::get('/songs/{song}/edit', [SongController::class, 'edit'])->name('songs.edit');

//曲：更新画面
Route::put('/songs/{song}', [SongController::class, 'update'])->name('songs.update');


//歌詞・動画
Route::get('/lyrics/{song}/edit', [LyricsController::class, 'edit'])->name('lyrics.edit');
Route::get('/choreography/{song}', [ChoreographyController::class, 'show'])->name('choreography.show');

//振り付け
Route::get('/choreography/{song}', [ChoreographyController::class, 'show'])->name('choreography.show');
Route::get('/choreography/{song}/edit', [ChoreographyController::class, 'edit'])->name('choreography.edit');
Route::put('/choreography/{song}', [ChoreographyController::class, 'update'])->name('choreography.update');

/**
* 「ログイン機能」インストールで追加されています 
*/
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
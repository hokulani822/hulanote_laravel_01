<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{
    public function index()
    {
        $songs = Song::orderBy('created_at', 'desc')->get();
        return view('songs.index', compact('songs'));
    }

    public function create()
    {
        return view('songs.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255',
            'artist' => 'required|min:3|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('songs.create')
                ->withErrors($validator)
                ->withInput();
        }

        $song = new Song;
        $song->user_id = Auth::id();
        $song->title = $request->title;
        $song->artist = $request->artist;
        $song->save();

        return redirect()->route('songs.index')->with('success', '新しい曲が追加されました。');
    }

    public function show(Song $song)
    {
        return view('songs.show', compact('song'));
    }

    public function edit(Song $song)
    {
        return view('songs.edit', compact('song'));
    }

    public function update(Request $request, Song $song)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255',
            'artist' => 'required|min:3|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('songs.edit', $song->id)
                ->withErrors($validator)
                ->withInput();
        }

        $song->title = $request->title;
        $song->artist = $request->artist;
        $song->save();

        return redirect()->route('songs.index')->with('success', '曲が更新されました。');
    }

    public function destroy(Song $song)
    {
        $song->delete();
        return redirect()->route('songs.index')->with('success', '曲が削除されました。');
    }
}
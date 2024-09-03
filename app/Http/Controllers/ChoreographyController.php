<?php

namespace App\Http\Controllers;

use App\Models\Choreography;
use App\Models\Song;
use Illuminate\Http\Request;

class ChoreographyController extends Controller
{
    public function show(Song $song)
    {
        $choreography = $song->choreography;
        return view('choreography.show', compact('song', 'choreography'));
    }

    public function edit(Song $song)
    {
        $choreography = $song->choreography;
        return view('choreography.edit', compact('song', 'choreography'));
    }

    public function update(Request $request, Song $song)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
        ]);

        $song->choreography()->updateOrCreate(
            ['song_id' => $song->id],
            $validated
        );

        return redirect()->route('choreography.show', $song)->with('success', '振り付け情報が更新されました。');
    }
}
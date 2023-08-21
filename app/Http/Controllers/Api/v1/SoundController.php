<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Sound\{ IndexSound, ShowSound, StoreSound, UpdateSound, DestroySound };
use App\Http\Resources\Api\v1\Sound\{ SoundIndexResource, SoundResource };
use App\Models\Sound;

class SoundController extends Controller
{
    /**
     * Display a listing of the sound.
     *
     * @param  \App\Http\Requests\Api\v1\Sound\IndexSound $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexSound $request)
    {
        $fields = $request->validated();
        $sounds = Sound::constrained($fields)->get();

        return response(new SoundIndexResource($sounds));
    }

    /**
     * Display the specified sound.
     *
     * @param  \App\Models\Sound  $sound
     * @param  \App\Http\Requests\Api\v1\Sound\ShowSound  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Sound $sound, ShowSound $request)
    {
        return response(new SoundResource($sound));
    }

    /**
     * Store a newly created sound in storage.
     *
     * @param  \App\Http\Requests\Api\v1\Sound\StoreSound  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSound $request)
    {
        $fields = $request->validated();
        $given = $request->file('sound');
        $sound = Sound::createFromFile($given, $fields);

        return response(new SoundResource($sound), 201);
    }

    /**
     * Update the specified sound in storage.
     *
     * @param  \App\Models\Sound  $sound
     * @param  \App\Http\Requests\Api\v1\Sound\UpdateSound  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Sound $sound, UpdateSound $request)
    {
        $fields = $request->validated();
        $given = $request->file('sound');

        if ($given) {
            $sound->updateFromFile($given, $fields);
        } else {
            $sound->fill($fields)->save();
        }

        return response(new SoundResource($sound));
    }

    /**
     * Remove the specified sound from storage.
     *
     * @param  \App\Models\Sound  $sound
     * @param  \App\Http\Requests\Api\v1\Sound\DestroySound  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sound $sound, DestroySound $request)
    {
        $sound->delete();
        return response(null, 204);
    }
}

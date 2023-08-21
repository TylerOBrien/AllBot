<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Folder\{ DestroyFolder, IndexFolder, ShowFolder, StoreFolder, UpdateFolder };
use App\Http\Resources\Api\v1\Folder\{ FolderIndexResource, FolderResource };
use App\Models\Folder;

class FolderController extends Controller
{
    /**
     * Respond with a listing of folders.
     *
     * @param  \App\Http\Requests\Api\v1\Folder\IndexFolder  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexFolder $request)
    {
        $fields = $request->validated();
        $query = Folder::with('children');
        $folders = Folder::constrained($fields, $query)->get();

        return response(new FolderIndexResource($folders));
    }

    /**
     * Respond with a folder.
     *
     * @param  \App\Http\Requests\Api\v1\Folder\ShowFolder  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ShowFolder $request, Folder $folder)
    {
        return response(new FolderResource($folder));
    }

    /**
     * Create a new folder instance and persist it in storage.
     *
     * @param  \App\Http\Requests\Api\v1\Folder\StoreFolder  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFolder $request)
    {
        $fields = $request->validated();
        $folder = Folder::create($fields)->fresh();

        return response(new FolderResource($folder), 201);
    }

    /**
     * Update the specified folder in storage.
     *
     * @param  \App\Http\Requests\Api\v1\Folder\UpdateFolder  $request
     * @param  \App\Models\Folder  $folder
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFolder $request, Folder $folder)
    {
        $fields = $request->validated();

        $folder->fill($fields);
        $folder->save();

        return response(new FolderResource($folder));
    }

    /**
     * Removes a folder instance from storage.
     *
     * @param  \App\Http\Requests\Api\v1\Folder\DestroyFolder  $request
     * @param  \App\Models\Folder  $folder
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyFolder $request, Folder $folder)
    {
        $folder->delete();
        return response(null, 204);
    }
}

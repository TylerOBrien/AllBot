<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Image\{ IndexImage, ShowImage, StoreImage, UpdateImage, DestroyImage };
use App\Http\Resources\Api\v1\Image\{ ImageIndexResource, ImageResource };
use App\Models\Image;

class ImageController extends Controller
{
    /**
     * Display a listing of the image.
     *
     * @param  \App\Http\Requests\Api\v1\Image\IndexImage $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexImage $request)
    {
        $fields = $request->validated();
        $images = Image::constrained($fields)->get();

        return response(new ImageIndexResource($images));
    }

    /**
     * Display the specified image.
     *
     * @param  \App\Models\Image  $image
     * @param  \App\Http\Requests\Api\v1\Image\ShowImage  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image, ShowImage $request)
    {
        return response(new ImageResource($image));
    }

    /**
     * Store a newly created image in storage.
     *
     * @param  \App\Http\Requests\Api\v1\Image\StoreImage  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImage $request)
    {
        $fields = $request->validated();
        $given = $request->file('image');
        $image = Image::createFromFile($given, $fields);

        return response(new ImageResource($image), 201);
    }

    /**
     * Update the specified image in storage.
     *
     * @param  \App\Models\Image  $image
     * @param  \App\Http\Requests\Api\v1\Image\UpdateImage  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Image $image, UpdateImage $request)
    {
        $fields = $request->validated();
        $given = $request->file('image');

        if ($given) {
            $image->updateFromFile($given, $fields);
        } else {
            $image->fill($fields)->save();
        }

        return response(new ImageResource($image));
    }

    /**
     * Remove the specified image from storage.
     *
     * @param  \App\Models\Image  $image
     * @param  \App\Http\Requests\Api\v1\Image\DestroyImage  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image, DestroyImage $request)
    {
        $image->delete();
        return response(null, 204);
    }
}

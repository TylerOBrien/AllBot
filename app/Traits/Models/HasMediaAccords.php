<?php

namespace App\Traits\Models;

use App\Enums\Accord\AccordName;
use App\Models\{ Accord, Image, Video };

trait HasMediaAccords
{
    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Creates a new Accord instance.
     *
     * @param  AccordName|string  $name  The name of the Accord relationship.
     * @param  array<string, mixed>  $fields  The attributes to pass to the Image create function.
     *
     * @return \App\Models\Accord
     */
    public function createMediaAccord(AccordName|string $name, array $fields): Accord
    {
        if (isset($fields['image']) || isset($fields['image_id'])) {
            return $this->createImageAccord($name, $fields);
        } else if (isset($fields['video']) || isset($fields['video_id'])) {
            return $this->createVideoAccord($name, $fields);
        }
    }

    /**
     * Update the specified Accord if it exists otherwise create a new one.
     *
     * @param  array<string, mixed>  $existing  The name of the Accord relationship.
     * @param  array<string, mixed>  $fields  The attributes to pass to the create function.
     *
     * @return \App\Models\Accord
     */
    public function updateOrCreateMediaAccord(array $existing, array $fields): Accord
    {
        if (isset($fields['image']) || isset($fields['image_id'])) {
            return $this->updateOrCreateImageAccord($existing, $fields);
        } else if (isset($fields['video']) || isset($fields['video_id'])) {
            return $this->updateOrCreateVideoAccord($existing, $fields);
        }
    }

    /**
     * Creates a new Accord instance.
     *
     * @param  AccordName|string  $name  The name of the Accord relationship.
     * @param  array<string, mixed>  $imageFields  The attributes to pass to the Image create function.
     *
     * @return \App\Models\Accord
     */
    public function createImageAccord(AccordName|string $name, array $imageFields): Accord
    {
        if ($name instanceof string) {
            $name = AccordName::from($name);
        }

        if (isset($imageFields['image'])) {
            $imageId = $this->createImage($name->value, $imageFields['image'], $imageFields)
                            ->id;
        } else {
            $imageId = $imageFields['image_id'];
        }

        return Accord::create([
            'name' => $name,
            'category' => $imageFields['category'] ?? null,
            'from_type' => self::class,
            'from_id' => $this->id,
            'to_type' => Image::class,
            'to_id' => $imageId,
        ]);
    }

    /**
     * Update the specified Accord if it exists otherwise create a new one.
     *
     * @param  array<string, mixed>  $existing  The name of the Accord relationship.
     * @param  array<string, mixed>  $fields  The attributes to pass to the create function.
     *
     * @return \App\Models\Accord
     */
    public function updateOrCreateImageAccord(array $existing, array $fields): Accord
    {
        $mergedFields = array_merge($existing, $fields);
        $query = Accord::where('from_type', self::class)
                       ->where('from_id', $this->id)
                       ->where('to_type', Image::class);

        foreach ($existing as $column => $value) {
            $query = $query->where($column, $value);
        }

        $accord = $query->first();

        if (is_null($accord)) {
            $accord = $this->createImageAccord($existing['name'], $mergedFields);
        } else {
            if (isset($fields['image'])) {
                $imageId = $this->createImage($existing['name']->value, $fields['image'], $mergedFields)
                                ->id;
            } else {
                $imageId = $fields['image_id'];
            }

            $accord->to_id = $imageId;
            $accord->save();
        }

        return $accord;
    }

    /**
     * Creates a new Accord instance.
     *
     * @param  AccordName|string  $name  The name of the Accord relationship.
     * @param  array<string, mixed>  $imageFields  The attributes to pass to the Video create function.
     *
     * @return \App\Models\Accord
     */
    public function createVideoAccord(AccordName|string $name, array $videoFields): Accord
    {
        if ($name instanceof string) {
            $name = AccordName::from($name);
        }

        if (isset($videoFields['video'])) {
            $videoId = $this->createVideo($name->value, $videoFields['video'], $videoFields)
                            ->id;
        } else {
            $videoId = $videoFields['video_id'];
        }

        return Accord::create([
            'name' => $name,
            'category' => $videoFields['category'] ?? null,
            'from_type' => self::class,
            'from_id' => $this->id,
            'to_type' => Video::class,
            'to_id' => $videoId,
        ]);
    }

    /**
     * Update the specified Accord if it exists otherwise create a new one.
     *
     * @param  array<string, mixed>  $existing  The name of the Accord relationship.
     * @param  array<string, mixed>  $fields  The attributes to pass to the create function.
     *
     * @return \App\Models\Accord
     */
    public function updateOrCreateVideoAccord(array $existing, array $fields): Accord
    {
        $mergedFields = array_merge($existing, $fields);
        $query = Accord::where('from_type', self::class)
                       ->where('from_id', $this->id)
                       ->where('to_type', Image::class);

        foreach ($existing as $column => $value) {
            $query = $query->where($column, $value);
        }

        $accord = $query->first();

        if (is_null($accord)) {
            $accord = $this->createVideoAccord($existing['name'], $mergedFields);
        } else {
            if (isset($fields['video'])) {
                $videoId = $this->createVideo($existing['name']->value, $fields['video'], $mergedFields)
                                ->id;
            } else {
                $videoId = $fields['video_id'];
            }

            $accord->to_id = $videoId;
            $accord->save();
        }

        return $accord;
    }
}

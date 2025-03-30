<?php

namespace App\Trait;

use App\Models\Image;

trait StoreImage
{
    public function storeImages($request, $model, $path)
    {
        $images = $request->file('images');
        foreach ($images as $image) {
            $name = uniqid() . rand(1, 9999) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($path), $name);
            Image::create([
                'url' => $path . '/' . $name,
                'imageable_id' => $model->id,
                'imageable_type' => get_class($model),
            ]);
        }
    }
    public function storeImage($image, $path)
    {
        $name = uniqid() . rand(1, 9999) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path($path), $name);
        return $path . '/' . $name;
    }

    public function deleteImages($model)
    {
        foreach ($model->images as $image) {
            $image->delete();
            unlink(public_path($image->url));
        }
    }

    public function deleteImage($image)
    {
        unlink(public_path($image));
    }

}

<?php
if (!function_exists('uploadBase64Image')) {
    function uploadBase64Image($base64, $path)
    {
        $extension = explode('/', explode(':', substr($base64, 0, strpos($base64, ';')))[1])[1];
        $imageName = Str::random(10) . '.' . $extension;
        @list($type, $image_data) = explode(';', $base64);
        @list(, $image_data) = explode(',', $image_data);
        $image = base64_decode($image_data);
        Storage::put($path . $imageName, $image);
        return $imageName;
    }
}

if (!function_exists('copyModelElementsToArray')) {
    function copyModelElementsToArray($model, $disclude)
    {
        foreach ($model->getAttributes() as $key => $value) {
            if (!in_array($key, $disclude)) {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }
}

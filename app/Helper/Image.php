<?php namespace App\Helper;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use function Sodium\crypto_box_seal;

class Image {

    private function _generateImageName($prefix, $id)
    {
        return $prefix.'_'.$id.'_'.bcrypt(Carbon::now());
    }

    public function save($prefix, $id, $photo) {
        $image_name = $this->_generateImageName($prefix, $id);
        Storage::disk('uploads')->put($image_name, $photo);
        return $image_name;
    }
    public function saveMany($prefix, $id, $photoArr) {
        foreach ($photoArr as $photo){
            $image_name = $this->_generateImageName($prefix, $id) . '.' . $photo->getClientOriginalExtension();
            Storage::disk('uploads')->put($image_name, $photo);
        }
        return $photoArr;
    }

}
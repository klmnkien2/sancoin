<?php namespace App\Helper;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Uploader
{
    const RESIZE_WIDTH_DEFAULT = 300;
    const RESIZE_HEIGHT_DEFAULT = 200;

    /**
     * @param $multiFiles
     * @param null $id
     * @param int $type
     * @param int $width
     * @param int $height
     * @return bool
     */
    public static function upload($multiFiles,  $id = null, $type = IMAGE_TYPE_BUKKEN, $width = self::RESIZE_WIDTH_DEFAULT, $height = self::RESIZE_HEIGHT_DEFAULT)
    {
        $arrPath = [];
        $arrPathThumb = [];
        $upload_status = true;
        if ($multiFiles) {
            foreach ($multiFiles as $files) {
                if(is_array($files)) {
                    foreach ($files as $file) {
                        try {
                            $filename = self::_getFileName($file->getClientOriginalName(), $id, $type);

                            if(in_array($file->getClientOriginalExtension(), self::getImageExtension())) {
                                $img = Image::make($file);
                                $resource = $img->resize($width, $height, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->stream()->detach();
                                $filename_thumb = $filename . IMAGE_THUMB_PREFIX . '.' . $file->getClientOriginalExtension();
                                Storage::put($filename_thumb, $resource);
                                array_push($arrPath, $filename_thumb);
                                array_push($arrPathThumb,$filename_thumb);

                            }
                            $filename_origin = $filename . '.' . $file->getClientOriginalExtension();
                            Storage::put($filename_origin, file_get_contents($file));
                            array_push($arrPath, $filename_origin);
                        } catch (\Exception $e) {
                            Log::error($e->getMessage());
                            Log::error($e->getTraceAsString());
                            Storage::delete($arrPath);
                            $upload_status = false;
                        }
                    }
                } else {
                    try {
                        $file = $files;
                        $filename = self::_getFileName($file->getClientOriginalName(), $id, $type);

                        if(in_array($file->getClientOriginalExtension(), self::getImageExtension())) {
                            $img = Image::make($file);
                            $resource = $img->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            })->stream()->detach();
                            $filename_thumb = $filename . IMAGE_THUMB_PREFIX . '.' . $file->getClientOriginalExtension();
                            Storage::put($filename_thumb, $resource);
                            array_push($arrPath, $filename_thumb);
                            array_push($arrPathThumb,$filename_thumb);

                        }
                        $filename_origin = $filename . '.' . $file->getClientOriginalExtension();
                        Storage::put($filename_origin, file_get_contents($file));
                        array_push($arrPath, $filename_origin);
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                        Log::error($e->getTraceAsString());
                        Storage::delete($arrPath);
                        $upload_status = false;
                    }
                }
            }
        }
        $rs = ['status'=>$upload_status,'data'=>$arrPathThumb];
        return $rs;
    }

    public static function delete($filename = null)
    {
        return  Storage::delete($filename);
    }

    /**
     * @param $fileName
     * @param $id
     * @param $type
     * @return string
     */
    private static function _getFileName($fileName, $id, $type)
    {
        $prefix = self::_getPrefixFileName($id, $type);
        return $prefix . md5($fileName) . time() . str_random(5);
    }

    /**
     * @param $id
     * @param $type
     * @return string
     */
    private static function _getPrefixFileName($id, $type)
    {
        if ($id)
            $id = $id . '_';
        return config('config.image.prefix.'.$type) . $id;
    }

    public static function getImageExtension()
    {
        return config('config.image.extension.image');
    }

    public static function getDocumentExtension()
    {
        return config('config.image.extension.document');
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use File;

class mainResource extends JsonResource
{
    function createUrl($file, $type, $extension = 'jpg')
    {
		
        if ( File::exists(public_path('data' . DIRECTORY_SEPARATOR .  $type  . DIRECTORY_SEPARATOR . $file . "." . $extension))) {
			if ( config('cache.images') == true ){
				return url("/data/{$type}/{$file}.{$extension}?r=" . time() );
			}else{
				return url("/data/{$type}/{$file}.{$extension}" );
			}
        } else {
			if ( $type == 'qrcode' ){
				return null ;
			}
            return url("/data/{$type}/blank.jpg");
        }
    }

    function imageSize($file, $type, $extension = 'jpg')
    {
        if (File::exists(public_path('data' . DIRECTORY_SEPARATOR .  $type  . DIRECTORY_SEPARATOR . $file . "." . $extension))) {
            $data = getimagesize(public_path("/data/{$type}/{$file}.{$extension}"));
            return ["width" => floatval($data[0]), "height" => floatval($data[1])];
        } else {
            return getimagesize(public_path("/data/{$type}/blank.png"));
        }
    }
}

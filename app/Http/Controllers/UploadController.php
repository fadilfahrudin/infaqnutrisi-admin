<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request) {
        $img = $request->file('file');
        $filename = 'image_'.time().'_'.$img->hashName();
        $img = $img->move(public_path('userfiles/images/'), $filename);
        $filelocation = url('userfiles/images/'.$filename);
        return response()->json(['location' => $filelocation]);

    }
    public function getUploadImg($img)
    {
        $names = explode(".", $img);
        $ext = end($names);
        $name = public_path('userfiles/images/'.$img);
        $fp = fopen($name, 'rb');

        switch( $ext ) {
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpeg"; break;
            case "svg": $ctype="image/svg+xml"; break;
            default:
        }

        // send the right headers
        header("Content-Type: ".$ctype);
        header("Content-Length: " . filesize($name));

        // dump the picture and stop the script
        fpassthru($fp);
        exit;

    }
}

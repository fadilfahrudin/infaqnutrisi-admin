<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageServiceProvider as Image;
class FileController extends Controller
{
    //
    public function create()
    {
        return view('news.upload.create');
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Gagal upload Gambar');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $image = $request->file('photo');
        // $img_name = $image->getClientOriginalExtension();
        $img_name = $image->getClientOriginalName();
        $filename = pathinfo($img_name, PATHINFO_FILENAME);
        $image->move(public_path('userfiles/images'), $filename.'.webp');
        $path = asset('userfiles/images/'.$filename.'.webp');
        $showImage = '<img src="'.$path.'" alt="" width="100" class="img-fluid"><br>' ;
        session()->flash('success', $showImage.'Berhasil upload Gambar');
        return redirect()->route('news.upload.create');
    }
}

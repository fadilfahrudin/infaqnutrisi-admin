<?php

namespace App\Http\Controllers;

use App\Models\Kajian;
use Exception;
use Illuminate\Http\Request;
use DB;
use File;
use Auth;
use Illuminate\Support\Facades\Validator;

class KajianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Kajian::all();
        return view('kajian.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kajian.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            session()->flash('error', 'Gagal upload Gambar');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        $r = new Kajian;
        $r->name = $request->input('name');
        $r->link = $request->input('link');
        $r->description = $request->input('description');
        $r->date_kajian = date('Y-m-d H:i:s', strtotime($request->input('date_kajian')));
        $r->published = $request->input('published');
        $r->created_at = date('Y-m-d H:i:s');
        $r->updated_at = date('Y-m-d H:i:s');
        $r->updated_by = Auth::user()->id;
        $r->created_by = Auth::user()->id;
        $image = $request->file('photo');
        $extension = $image->getClientOriginalExtension();
        $new_img_name = 'KAJIAN-'.date('YmdHis').'.'.$extension;
        $image->move(public_path('img/photo-kajian'), $new_img_name);
        $r->photo = $new_img_name;
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Gagal insert data '.$e->getMessage);
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil input kajian');
        return redirect()->route('kajian');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $r = Kajian::find($id);
        return view('kajian.edit', compact('r'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            session()->flash('error', 'Gagal upload Gambar');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        $r = Kajian::find($id);
        $r->name = $request->input('name');
        $r->link = $request->input('link');
        $r->description = $request->input('description');
        $r->date_kajian = date('Y-m-d H:i:s', strtotime($request->input('date_kajian')));
        $r->published = $request->input('published');
        $r->updated_at = date('Y-m-d H:i:s');
        $r->updated_by = Auth::user()->id;
        if ($request->hasFile('photo')) {
            $old_img_name = public_path('img/photo-berita/'.$r->photo);
            File::delete($old_img_name);
            $image = $request->file('photo');
            $extension = $image->getClientOriginalExtension();
            $new_img_name = 'KAJIAN-'.date('YmdHis').'.'.$extension;
            $image->move(public_path('img/photo-kajian'), $new_img_name);
            $r->photo = $new_img_name;
        }
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Gagal insert data '.$e->getMessage);
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil update kajian');
        return redirect()->route('kajian');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

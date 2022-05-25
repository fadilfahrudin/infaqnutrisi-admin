<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Auth;
use App\Models\Master;

class PromosiController extends Controller
{
    const ROUTE_GROUP = 'promosi';
    const VIEW_DIR = 'promosi';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Konten Promosi';
        $rows = Master::where([
            ['datagroup', 'promosi']
        ])->get();
        $route = self::ROUTE_GROUP;
        return view(self::VIEW_DIR.'.index', compact('title', 'rows', 'route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Tambah Konten Promosi Baru';
        $rows = Master::where([
            ['datagroup', 'promosi'],
            ['is_active', 1]
        ])->get();
        $route = self::ROUTE_GROUP;
        return view(self::VIEW_DIR.'.create', compact('title', 'rows', 'route'));
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
        $jenis = $request->input('attribute1');
        $insert_after = $request->input('insert_after');

        DB::beginTransaction();
        $r = new Master;
        $r->datagroup = 'promosi';
        $r->code = Str::slug($request->input('name'), '-');
        $r->name = $request->input('name');
        $r->description = $request->input('description');
        $r->ordering = !empty($insert_after) ? $insert_after + 1 : 1;
        $r->attribute1 = $request->input('attribute1');
        $r->attribute2 = $request->input('attribute2');
        $r->is_active = $request->input('is_active');
        $r->created_at = date('Y-m-d H:i:s');
        $r->created_by = Auth::user()->id;
        $image = $request->file('photo');
        $extension = $image->getClientOriginalExtension();
        $new_img_name = $jenis.'-'.date('YmdHis').'.'.$extension;
        $image->move(public_path('img/promosi'), $new_img_name);
        $r->attribute3 = $new_img_name;
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Gagal menyimpan data '.$e->getMessage);
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil menyimpan data');
        return redirect()->route(self::ROUTE_GROUP);
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
        $title = 'Edit Konten Promosi';
        $row = Master::find($id);
        $rows = Master::where([
            ['datagroup', 'promosi'],
            ['is_active', 1]
        ])->get();
        $route = self::ROUTE_GROUP;
        return view(self::VIEW_DIR.'.edit', compact('title', 'row', 'rows', 'route'));
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
        $jenis = $request->input('attribute1');
        $insert_after = $request->input('insert_after');

        DB::beginTransaction();
        $r = Master::find($id);
        $r->datagroup = 'promosi';
        $r->code = Str::slug($request->input('name'), '-');
        $r->name = $request->input('name');
        $r->description = $request->input('description');
        $r->ordering = !empty($insert_after) ? $insert_after + 1 : 1;
        $r->attribute1 = $request->input('attribute1');
        $r->attribute2 = $request->input('attribute2');
        $r->is_active = $request->input('is_active');
        $r->updated_at = date('Y-m-d H:i:s');
        $r->updated_by = Auth::user()->id;
        $image = $request->file('photo');
        $extension = $image->getClientOriginalExtension();
        $new_img_name = $jenis.'-'.date('YmdHis').'.'.$extension;
        $image->move(public_path('img/promosi'), $new_img_name);
        $r->attribute3 = $new_img_name;
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Gagal memperbaharui data '.$e->getMessage);
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil memperbaharui data');
        return redirect()->route(self::ROUTE_GROUP);
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

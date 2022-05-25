<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Exception;
use Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

class MitraController extends Controller
{
    public function index()
    {
        $rows = Mitra::all();
        return view('mitra.index', compact('rows'));
    }
    public function create()
    {
        return view('mitra.create');
    }
    public function insert(Request $request)
    {   
        DB::beginTransaction();
        $r = new Mitra;
        $r->name = $request->input('name');
        $r->phone = $request->input('phone');
        $r->email = $request->input('email');
        if ($request->input('password') == $request->input('conpass')) {
            $r->password = Hash::make($request->input('password'));
        }
        if ($request->input('refcode') != null) {
            $r->refcode = $request->input('refcode');
        } else {
            $code = Str::random(5);
            $check = Mitra::where('refcode', $code)->first();
            $r->refcode = ($check && !empty($check)) ? Str::random(5) : $code;
        }
        $r->role = $request->input('role');
        $r->is_active = 0;
        $r->created_at = date('Y-m-d H:i:s');
        $r->updated_at = date('Y-m-d H:i:s');
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Gagal insert data '.$e->getMessage());
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil create user');
        return redirect()->route('mitra'); 
    }
    public function edit($id)
    {
        $r = Mitra::find($id);
        return view('mitra.edit', compact('r'));
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $r = Mitra::find($id);
        $r->name = $request->input('name');
        $r->phone = $request->input('phone');
        $r->email = $request->input('email');
        if (!empty($request->input('password')) && $request->input('password') == $request->input('conpass')) {
            $r->password = Hash::make($request->input('password'));
        }
        $r->refcode = $request->input('refcode');
        $r->updated_at = date('Y-m-d H:i:s');
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Gagal update user '.$e->getMessage());
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil update user '.$r->name);
        return redirect()->route('mitra'); 
    }
    public function delete(Request $request)
    {
        $data = Mitra::find($request->input('id'));
        if($data->delete()) {
            return redirect()->route('mitra')->with('success','Data User berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }
    public function valEmail(Request $request)
    {
        $email = $request->input('email');
        $checkEmail = Mitra::where('email', '=', $email )->exists();
        return $checkEmail ? 'true' : 'false';
    }
    public function valRefcode(Request $request)
    {
        $refcode = $request->input('refcode');
        $checkRefcode = Mitra::where('refcode', '=', $refcode)->exists();
        return $checkRefcode ? 'true' : 'false';
    }
}

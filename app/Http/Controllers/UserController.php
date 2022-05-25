<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.index', compact('user'));
    }
    public function submitProfile(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'nama'      => 'required',
            'email'     => 'required',
        ],[
            'required'  => 'Kolom :attribute harus diisi',
            'unique'    => ':attribute sudah dipakai, harap masukkan :attribute yang lain.'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $r = User::findOrFail($id);
        $r->name = $request->input('nama');
        $r->email = $request->input('email');
        $r->updated_at = date('Y-m-d H:i:s');
        if($r->save()) {
            return redirect()->back()->with('success', 'Berhasil update data');
        }
    }
    public function changePassword(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'password'          => 'required',
            'confirm_password'  => 'required'
        ],[
            'required'  => 'Kolom :attribute harus diisi'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $r = User::findOrFail($id);
        if($request->input('password') == $request->input('confirm_password')) {
            $r->password = Hash::make($request->input('password'));
            $r->updated_at = date('Y-m-d H:i:s');
            if($r->save()) {
                return redirect()->back()->with('success-password', 'Berhasil update data');
            }
        } else {
            return redirect()->back()->with('error', 'Password baru dan Ulangi Password baru harus sama');
        }
    }
    public function indexAdmin()
    {
        $rows = User::all();
        return view('admin.index', compact('rows'));
    }
    public function createAdmin()
    {
        return view('admin.create');
    }
    public function insertAdmin(Request $request)
    {
        DB::beginTransaction();
        $r = new User;
        $r->name = $request->input('name');
        $r->email = $request->input('email');
        if ($request->input('password') == $request->input('conpass')) {
            $r->password = Hash::make($request->input('password'));
        }
        $r->is_super = $request->input('is_super');
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
        session()->flash('success', 'Berhasil create user admin');
        return redirect()->route('admin');
    }
    public function editAdmin($id)
    {
        $r = User::find($id);
        return view('admin.edit', compact('r'));
    }
    public function updateAdmin(Request $request, $id)
    {
        DB::beginTransaction();
        $r = User::find($id);
        $r->name = $request->input('name');
        $r->email = $request->input('email');
        if ($request->input('password') == $request->input('conpass')) {
            $r->password = Hash::make($request->input('password'));
        }
        $r->is_super = $request->input('is_super');
        $r->created_at = date('Y-m-d H:i:s');
        $r->updated_at = date('Y-m-d H:i:s');
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
            // session()->flash('error', 'Gagal update data '.$e->getMessage());
            // return redirect()->back();
        }
        session()->flash('success', 'Berhasil update user admin');
        return redirect()->route('admin');
    }
    public function deleteAdmin(Request $request)
    {
        $data = User::find($request->input('id'));
        if($data->delete()) {
            return redirect()->route('admin')->with('success','Data User berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }
}

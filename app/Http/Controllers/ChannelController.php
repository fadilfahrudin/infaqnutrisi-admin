<?php

namespace App\Http\Controllers;

use App\Models\PaymentChannel;
use Illuminate\Http\Request;
use DB;
use Exception;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = PaymentChannel::all();
        return view('channels.index', compact('channels'));
    }
    public function create()
    {
        return view('channels.create');
    }
    public function insert(Request $request)
    {
        DB::beginTransaction();
        $r = new PaymentChannel;
        $r->name = $request->input('name');
        $r->code = $request->input('code');
        $r->group_type = $request->input('group_type');
        $r->vendor = $request->input('vendor');
        $r->vendor_var_code = $request->input('vendor_var_code');
        $r->vendor_var_no = $request->input('vendor_var_no');
        $r->account_number = $request->input('account_number');
        $r->account_name = $request->input('account_name');
        $r->is_active = $request->input('is_active');
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Gagal update channel pembayaran');
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil update channel pembayaran');
        return redirect()->route('channel');       
    }
    public function edit($id)
    {
        $r = PaymentChannel::find($id);
        return view('channels.edit', compact('r'));
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $r = PaymentChannel::findOrFail($id); 
        $r->name = $request->input('name');
        $r->code = $request->input('code');
        $r->group_type = $request->input('group_type');
        $r->vendor = $request->input('vendor');
        $r->vendor_var_code = $request->input('vendor_var_code');
        $r->vendor_var_no = $request->input('vendor_var_no');
        $r->account_number = $request->input('account_number');
        $r->account_name = $request->input('account_name');
        $r->is_active = $request->input('is_active');
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Gagal update channel pembayaran');
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil update channel pembayaran');
        return redirect()->route('channel');     

    }
}

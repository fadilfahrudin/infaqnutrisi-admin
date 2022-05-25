<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{

    public function index()
    {
        $rows = Withdraw::all();
        return view('withdraw.index', compact('rows'));

    }

    public function create()
    {
        $programs = Program::where('published', 1)->get();    
        return view('withdraw.create', compact('programs'));
    }

    public function insert(Request $request)
    {

        DB::beginTransaction();
        $r = new Withdraw();
        $r->request_date = date('Y-m-d');
        $r->program_id = $request->input('program_id');
        $r->mitra_id = $request->input('mitra_id');
        $r->donation_start_date = date('Y-m-d', strtotime($request->input('donation_start_date')));
        $r->donation_end_date = date('Y-m-d', strtotime($request->input('donation_end_date'))); 
        $r->donation_collected = $request->input('donation_collected');
        $r->payable_amount = $request->input('payable_amount');
        $r->infaq_pengembangan = $request->input('infaq_pengembangan');
        $r->biaya_iklan = $request->input('biaya_iklan');
        $r->biaya_payment_gateway = $request->input('biaya_payment_gateway');
        $r->details = $request->input('details');
        $r->created_at = date('Y-m-d H:i:s');
        $r->updated_at = date('Y-m-d H:i:s');
        $r->updated_by = Auth::user()->id;
        $r->created_by = Auth::user()->id;
        $r->status = 'new';
        try {

            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal insert data'.$e->getMessage);
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil membuat form pencairan');
        return redirect()->route('withdraw'); 
        
    }

    public function detail($id)
    {
        $r = Withdraw::find($id);
        $program = Program::find($r->program_id);

        return view('withdraw.detail', compact('r', 'program'));
    }

    public function updateDetail(Request $request, $id)
    {

        $validator = Validator::make($request->all(),[
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            session()->flash('error', 'Gagal insert data');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        DB::beginTransaction();
        $r = Withdraw::findOrFail($id);

        $r->transfer_from = $request->input('transfer_from');
        $r->updated_at = date('Y-m-d H:i:s');
        $r->updated_by = Auth::user()->id;
        $r->status = 'approved';

        if ($request->hasFile('bukti_transfer')) {
            $image = $request->file('bukti_transfer');
            $extension = $image->getClientOriginalExtension();
            $new_img_name = 'BT-'.date('dmyhis').'.'.$extension;
            $image->move(public_path('img/bukti-transfer'), $new_img_name);
            $r->bukti_transfer = $new_img_name;
        }else{
            return $request;
            $r->bukti_transfer = '';
        }
        try {
            $r->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal insert data');
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil update form pencairan');
        return redirect()->route('withdraw');


    }

    public function cancelRequest($id)
    {
        DB::beginTransaction();
        $r =  Withdraw::findOrFail($id);
        
        $r->updated_at = date('Y-m-d H:i:s');
        $r->updated_by = Auth::user()->id;
        $r->status = 'cancelled';

        $r->save();
        DB::commit();

        session()->flash('cancel', 'Berhasil membatalkan form pencairan');
        return redirect()->route('withdraw');
    }

    public function valDate(Request $request)
    {
        $programId = $request->input('program_id');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal'))); 
        if ($request->input('tipe') == 'start') {
            $checkStartDate = Withdraw::where([
                ['program_id', $programId],
                ['status', '=','approved'],
                ['donation_start_date', $tanggal]
            ])->exists();

            return $checkStartDate ? 'true' : 'false';
        } else {
            $checkEndDate = Withdraw::where([
                ['program_id', $programId],
                ['status', '=','approved'],
                ['donation_end_date', $tanggal]
            ])->exists();

            return  $checkEndDate ? 'true' : 'false' ;
        }
    }
}

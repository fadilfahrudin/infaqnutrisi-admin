<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;

use App\Models\QurbanPage;
use App\Models\QurbanPackage;
use App\Models\QurbanOrder;
use App\Models\QurbanOrderDetail;
use App\Models\PaymentChannel;

class QurbanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('qurban.index');
    }
    public function indexPage()
    {
        $rows = QurbanPage::all();
        $route = 'qurban.page';
        return view($route.'.index', compact('rows','route'));
    }
    public function indexPackage()
    {
        $rows = QurbanPackage::all();
        $route = 'qurban.package';
        return view($route.'.index', compact('rows','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $packages = QurbanPackage::where('is_active', 1)->get();
        $channels = PaymentChannel::where('shown_in_admin', 1)->get();
        return view('qurban.create', compact('packages','channels'));
    }
    public function createPage()
    {
        $route = 'qurban.page';
        return view('qurban.page.create', compact('route'));
    }
    public function createPackage()
    {
        $route = 'qurban.package';
        return view($route.'.create', compact('route'));
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
            'transaction_code' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'total_amount' => 'required'
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Field tidak boleh kosong');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        $data = new QurbanOrder;
        $data->transaction_code = $request->input('transaction_code');
        $data->refcode = $request->input('refcode');
        $data->customer_name = $request->input('customer_name');
        $data->customer_phone = $request->input('customer_phone');
        $data->customer_email = $request->input('customer_email');
        $total_amount = $request->input('total_amount');
        $tfCode = $request->input('transfer_code');
        $data->transfer_code = $tfCode;
        $data->total_amount = $total_amount;
        $data->total_amount_final = !empty($tfCode) ? $total_amount + $tfCode : $total_amount;
        $payment_channel_id = $request->input('payment_channel_id');
        $payment = PaymentChannel::find($payment_channel_id);
        $data->payment_channel_id = $payment_channel_id;
        $data->payment_channel_vendor = $payment->vendor;
        $data->payment_channel_type = $payment->group_type;
        $data->payment_channel_code = $payment->code;
        $data->payment_channel_name = $payment->name;
        $data->payment_channel_number = $request->input('payment_channel_number');
        $data->payment_initiated = date('Y-m-d H:i:s');
        $data->payment_finished = date('Y-m-d H:i:s');
        $data->status = 'done';
        $data->created_at = date('Y-m-d H:i:s');
        $data->updated_by = Auth::id();
        $data->approved_by = Auth::id();

        try {
            $data->save();
            $package = QurbanPackage::where('is_active', 1)->get();
            foreach($package as $p) {
                if(!empty($request->input('qty-'.$p->id))) {
                    $qty = $request->input('qty-'.$p->id);
                    $detail = new QurbanOrderDetail;
                    $detail->order_id = $data->id;
                    $detail->package_id = $p->id;
                    $detail->qty = $qty;
                    $detail->price = $p->price;
                    $detail->subtotal = $qty * $p->price;
                    $detail->notes = str_replace(',','|',$request->input('notes-'.$p->id));
                    $detail->save();
                }
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            session()->flash('error', 'Gagal update channel pembayaran');
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil menyimpan data');
        return redirect()->route('qurban.transaction.index');
    }
    public function storePage(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Gagal upload foto. Pastikan tipe file sudah sesuai');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = new QurbanPage;
        $data->name = $request->input('name');
        $data->pitch = $request->input('pitch');
        $data->description = $request->input('description');

        if($request->file('photo')) {
            $photo = $request->file('photo');
            $extphoto = $photo->getClientOriginalExtension();
            $filephoto = 'qurban_'.date('ymdHis').'.'.$extphoto;
            if($photo->move(public_path('img/pages'), $filephoto)) {
                $data->photo = $filephoto;
            }
        }
        if($request->file('banner_photo')) {
            $banner_photo = $request->file('banner_photo');
            $extbphoto = $banner_photo->getClientOriginalExtension();
            $filebphoto = 'banner_qurban_'.date('ymdHis').'.'.$extbphoto;
            if($banner_photo->move(public_path('img/pages'), $filebphoto)) {
                $data->banner_photo = $filebphoto;
            }
        }
        $data->published = $request->input('published');
        $data->created_at = date('Y-m-d H:i:s');
        $data->created_by = Auth::user()->id;

        try {
            $data->save();
        } catch(Exception $e) {
            session()->flash('error', 'Gagal menyimpan data '.$e->getMessage);
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil menyimpan data');
        return redirect()->route('qurban.page.index');
    }
    public function storePackage(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'banner' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Gagal upload foto. Pastikan tipe file sudah sesuai');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = new QurbanPackage;
        $data->category = $request->input('category');
        $data->name = $request->input('name');
        $data->description = $request->input('description');
        $data->price = $request->input('price');
        $data->area = $request->input('area');

        if($request->file('banner')) {
            $photo = $request->file('banner');
            $extphoto = $photo->getClientOriginalExtension();
            $filephoto = 'paket_'.date('ymdHis').'.'.$extphoto;
            if($photo->move(public_path('img/packages'), $filephoto)) {
                $data->banner = $filephoto;
            }
        }
        $data->is_active = $request->input('is_active');
        $data->created_at = date('Y-m-d H:i:s');
        $data->created_by = Auth::user()->id;

        try {
            $data->save();
        } catch(Exception $e) {
            session()->flash('error', 'Gagal menyimpan data '.$e->getMessage);
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil menyimpan data');
        return redirect()->route('qurban.package.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $r = QurbanOrder::findOrFail($id);
        $detail = QurbanOrderDetail::where('order_id', $id)->get();
        return view('qurban.detail', compact('r','detail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    public function editPage($id)
    {
        $row = QurbanPage::find($id);
        $route = 'qurban.page';
        return view('qurban.page.edit', compact('row','route'));
    }
    public function editPackage($id)
    {
        $row = QurbanPackage::find($id);
        $route = 'qurban.package';
        return view('qurban.package.edit', compact('row','route'));
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
        //
    }
    public function updatePage(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Gagal upload foto. Pastikan tipe file sudah sesuai');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = QurbanPage::find($id);
        $data->name = $request->input('name');
        $data->pitch = $request->input('pitch');
        $data->description = $request->input('description');

        if($request->file('photo')) {
            $photo = $request->file('photo');
            $extphoto = $photo->getClientOriginalExtension();
            $filephoto = 'qurban_'.date('ymdHis').'.'.$extphoto;
            if($photo->move(public_path('img/pages'), $filephoto)) {
                $data->photo = $filephoto;
            }
        }
        if($request->file('banner_photo')) {
            $banner_photo = $request->file('banner_photo');
            $extbphoto = $banner_photo->getClientOriginalExtension();
            $filebphoto = 'banner_qurban_'.date('ymdHis').'.'.$extbphoto;
            if($banner_photo->move(public_path('img/pages'), $filebphoto)) {
                $data->banner_photo = $filebphoto;
            }
        }
        $data->published = $request->input('published');
        $data->updated_at = date('Y-m-d H:i:s');
        $data->updated_by = Auth::user()->id;

        try {
            $data->save();
        } catch(Exception $e) {
            session()->flash('error', 'Gagal update data '.$e->getMessage);
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil update data');
        return redirect()->route('qurban.page.index');
    }
    public function updatePackage(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'banner' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Gagal upload foto. Pastikan tipe file sudah sesuai');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = QurbanPackage::find($id);
        $data->category = $request->input('category');
        $data->name = $request->input('name');
        $data->description = $request->input('description');
        $data->price = $request->input('price');
        $data->area = $request->input('area');

        if($request->file('banner')) {
            $photo = $request->file('banner');
            $extphoto = $photo->getClientOriginalExtension();
            $filephoto = 'paket_'.date('ymdHis').'.'.$extphoto;
            if($photo->move(public_path('img/packages'), $filephoto)) {
                $data->banner = $filephoto;
            }
        }
        $data->is_active = $request->input('is_active');
        $data->updated_at = date('Y-m-d H:i:s');
        $data->updated_by = Auth::user()->id;

        try {
            $data->save();
        } catch(Exception $e) {
            session()->flash('error', 'Gagal menyimpan data '.$e->getMessage);
            return redirect()->back();
        }
        session()->flash('success', 'Berhasil menyimpan data');
        return redirect()->route('qurban.package.index');
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
    public function filter(Request $request) {
        $validator = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        $status = $request->input('status');
        if(!empty($status)) {
            $rows = QurbanOrder::where('status', $request->input('status'))
                ->whereBetween('payment_initiated', [$request->input('start_date'), $request->input('end_date')])
                ->get();
        } else {
            $rows = QurbanOrder::whereBetween('payment_initiated', [$request->input('start_date'), $request->input('end_date')])
                ->get();
        }
        $html = '<table id="datatable" class="table table-bordered table-sm table-hover table-striped" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No. Pembayaran</th><th>Fundraiser</th><th>Tgl. Invoice</th><th>Tgl. Bayar</th><th>Nama</th><th>Jumlah</th><th>Bayar via</th><th>Status</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>';
        foreach($rows as $r) {
            $amount = !empty($r->total_amount_final) ? number_format($r->total_amount_final,0,',','.') : '&infin;';
            $status = $r->status == 'pending' ? ' badge-secondary' : ($r->status == 'done' ? ' badge-success' : ' badge-warning');
            $payInit = !empty($r->payment_initiated) ? date('Y/m/d', strtotime($r->payment_initiated)) : '';
            $payFinish = !empty($r->payment_finished) ? date('Y/m/d', strtotime($r->payment_finished)) : '';
            $fundraiser = !empty($r->refcode) ? $r->fundraiser->name : 'N/A';
            $html .= '<tr>
                <td>'.$r->transaction_code.'</td>
                <td>'.$fundraiser.'</td>
                <td>'.$payInit.'</td>
                <td>'.$payFinish.'</td>
                <td>'.$r->customer_name.'</td>
                <td class="text-right">'.$amount.'</td>
                <td class="text-center">'.$r->payment_channel_name.'</td>
                <td class="text-center"><span class="badge '.$status.'">'.$r->status.'</span></td>';
            
            if ($r->payment_channel_type == 'bankreg' && $r->status == 'done') {
                $html .= '<td>
                    <a href="'.url('/qurban/transaction/'.$r->id.'/edit').'" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i>&nbsp;Edit
                    </a>
                </td>';
            } else {
                $html .= '<td>
                    <a href="'.url('/qurban/transaction/'.$r->id.'/detail').'" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i>&nbsp;Detail
                    </a>
                </td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        return response()->json(['success' => true, 'html' => $html]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\Program;
use Auth;
use DB;
use App\Models\Amal;
use App\Models\PaymentChannel;

class AmalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $rows = Amal::all();
        return view('amal.index', compact('rows'));
    }
    public function detail($id) {
        $r = Amal::findOrFail($id);
        return view('amal.detail', compact('r'));
    }
    public function create() {
        $channels = PaymentChannel::where('is_active', 1)
            ->orderBy('id', 'asc')->get();
        return view('amal.create', compact('channels'));
    }
    public function store(Request $request) {
        $channel = PaymentChannel::findOrFail($request->input('payment_channel_id'));
        $r = new Amal;
        $r->transaction_code = $request->input('transaction_code');
        $r->funder_name = $request->input('name');
        $r->funder_ishidden = $request->input('isHidden');
        $r->funder_phone = $request->input('phone');
        $r->funder_email = $request->input('email');
        $r->payment_channel_id = $request->input('payment_channel_id');
        $r->payment_channel_type = $channel->group_type;
        $r->payment_channel_code = $channel->group_type == 'bankreg' ? $channel->code : $channel->vendor_var_code;
        $r->payment_channel_name = $channel->name;
        $r->payment_account_number = $request->input('payment_account_number');
        $r->amount = $request->input('amount_final');
        $r->amount_final = $request->input('amount_final');
        $r->payment_initiated = date('Y-m-d H:i:s', strtotime($request->input('payment_finished')));
        $r->payment_finished = date('Y-m-d H:i:s', strtotime($request->input('payment_finished')));
        $r->payment_expired = date('Y-m-d H:i:s', strtotime($request->input('payment_finished')));
        $r->status = 'done';
        $r->created_at = date('Y-m-d H:i:s');
        $r->updated_at = date('Y-m-d H:i:s');
        $r->updated_by = Auth::user()->id;
        $r->approved_by = Auth::user()->id;
        try {
            $r->save();
            if(!empty($r->funder_phone)) {
                $amount_transfered = number_format($data->amount_final,0,',','.');
                $phone = trim($r->funder_phone);
                $message = '*AAJARAKALLAHU FIIMAA A’THAITA WA BAARAKA LAKA FIIMAA ABQAITA WA JA’ALAHU LAKA THAHUURAN*
    
"Semoga Allah memberi pahala atas apa yang telah engkau berikan, melimpahkan berkah terhadap hartamu yang tersisa dan menjadikannya penyuci bagimu ".

Terima kasih *'.$r->funder_name.'!* 😊

Kami telah menerima donasi dari Anda pada rekening bank kami sebesar *Rp '.$amount_transfered.'* 🙏

Donasi yang masuk pada *Kotak Amal*, akan tetap kami salurkan kepada program yang membutuhkan dengan mengutamakan prinsip *tepat guna, tepat sasaran, dan tepat waktu*. 🤝

Untuk pelaporan penggunaan donasi, nantinya bisa anda pantau melalui laman *Kotak Amal* kami 📝

Yuk, bantu *#SemangatBantu* dengan menyebarkan info program-program yang ada ke orang-orang terdekat Anda melalui situs www.semangatbantu.com 🗣️';
                $this->sayThanksWA($phone,$message);
            }
            return redirect()->route('amal')->with('success','Data Kotak Amal berhasil disimpan');
        } catch(Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data. '.$e->getMessage);
        }
    }
    function sayThanksWA($phone,$message) {
        $curl = curl_init();
        // $token = 'tzfr2LrIpTVv60KKmtUZ2SYuADZNyayhbso8vpENzruXKzVt0bW7ZaBktLVePPkp';
        $token = 'SYTieFBjs1ChNSR0Mt9cuBNkvoSEusVB7fs0FNIAy7RQtubLKZmHJEgObTlIsFIT';
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, "https://sambi.wablas.com/api/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
    }
    public function delete(Request $request) {
        $data = Amal::findOrFail($request->input('id'));
        if($data->delete()) {
            return redirect()->route('amal')->with('success','Data Kotak Amal berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }
}
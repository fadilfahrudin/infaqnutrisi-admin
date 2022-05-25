<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\Program;
use Auth;
use DB;
use App\Models\Amal;
use App\Mail\PaymentThankYou;
use App\Models\Mitra;
use Illuminate\Support\Facades\Mail;
use App\Models\PaymentChannel;
use App\Models\DonasiReplies;

class DonasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['sendMail']]);
    }
    public function index() {
        return view('donasi.index');
    }
    public function create() {
        $programs = Program::where('published', 1)->get();
        $channels = PaymentChannel::where('shown_in_admin', 1)->get();
        return view('donasi.create', compact('programs','channels'));
    }
    public function insert(Request $request) {
        if($request->input('program_id') && $request->input('program_id') != '' && $request->input('payment_channel_id') && $request->input('payment_channel_id') != '') {
            $program = Program::findOrFail($request->input('program_id'));
            $channel = PaymentChannel::findOrFail($request->input('payment_channel_id'));
            DB::beginTransaction();
            $r = new Donasi;
            $r->transaction_code = $request->input('transaction_code');
            $r->program_id = $request->input('program_id');
            $r->funder_name = $request->input('funder_name');
            $r->funder_phone = $request->input('funder_phone');
            $r->funder_email = $request->input('funder_email');
            $r->payment_channel_id = $request->input('payment_channel_id');
            $r->payment_channel_vendor = $channel->vendor;
            $r->payment_channel_type = $channel->group_type;
            $r->payment_channel_code = $channel->code;
            $r->payment_channel_name = $channel->name;
            $r->payment_account_number = $request->input('payment_account_number');
            $r->amount = $request->input('amount_final');
            $r->amount_final = $request->input('amount_final');
            $r->payment_initiated = date('Y-m-d H:i:s', strtotime($request->input('payment_finished')));
            $r->payment_finished = date('Y-m-d H:i:s', strtotime($request->input('payment_finished')));
            $r->payment_expired = date('Y-m-d H:i:s', strtotime($request->input('payment_finished') . ' + 4 hours'));
            $r->status = 'done';
            $r->created_at = date('Y-m-d H:i:s');
            $r->updated_at = date('Y-m-d H:i:s');
            $r->updated_by = Auth::user()->id;
            $r->approved_by = Auth::user()->id;
            try {
                $r->save();
                // $terkumpul = $program->collected;
                // $collected = $terkumpul + $request->input('amount_final');
                $collected = Donasi::where([
                    ['program_id', '=', $program->id],
                    ['status', '=', 'done']
                ])->sum('amount_final');
                $program->collected = $collected;
                if(!empty($program->target_amount)) {
                    $progress = floor(($collected / $program->target_amount) * 100);
                    $program->progress = $progress;
                }
                $program->save();
                DB::commit();
                if(!empty($request->input('funder_phone'))) {
                    $link = 'https://semangatbantu.com/p/'.$program->link;
                    $amount = number_format($r->amount_final,0,',','.');

                /* SEND NOTIF PAYMENT RECEIVED TO WA */
                    $phone = trim($r->funder_phone);
                    $message = '*AAJARAKALLAHU FIIMAA A’THAITA WA BAARAKA LAKA FIIMAA ABQAITA WA JA’ALAHU LAKA THAHUURAN*

"Semoga Allah memberi pahala atas apa yang telah engkau berikan, melimpahkan berkah terhadap hartamu yang tersisa dan menjadikannya penyuci bagimu ".

Terima kasih *'.$r->funder_name.'!* 😊

Donasi Anda sebesar *Rp '.$amount.'* sudah diterima untuk penggalangan dana program *'.$program->name.'* di *'.$link.'* 👈

Yuk, bantu *#SemangatBantu* dengan menyebarkan penggalangan ini ke orang-orang terdekat Anda. 🗣️';
                    $this->sayThanksWA($phone,$message);
                }
                if(!empty($request->input('funder_email'))) {
                    try {
                        Mail::to($r->funder_email)
                            ->send(new PaymentThankYou($r));
                    } catch(Exception $e) {
                        return redirect()->back()->with('Gagal mengirim E-Mail ke '.$r->funder_email);
                    }
                }
            } catch(Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal insert data. '.$e->getMessage);
            }
            return redirect()->route('donasi')->with('success','Input Donasi Baru berhasil');
        }
    }
    public function detail($id) {
        $r = Donasi::findOrFail($id);
        return view('donasi.detail', compact('r'));
    }
    public function update(Request $request, $id) {
        if($request->input('next-status') == 'done') {
            DB::beginTransaction();
            $r = Donasi::findOrFail($id);
            if($r) {
                $r->payment_finished = $request->input('payment_finished');
                $r->amount_final = $request->input('accepted_amount');
                $r->status = 'done';
                $r->updated_at = date('Y-m-d H:i:s');
                $r->updated_by = Auth::user()->id;
                $r->approved_by = Auth::user()->id;
                $program = Program::findOrFail($r->program_id);
                if($program && $r->save()) {
                    // $terkumpul = $program->collected;
                    // $collected = $terkumpul + $request->input('amount_final');
                    $collected = Donasi::where([
                        ['program_id', '=', $program->id],
                        ['status', '=', 'done']
                    ])->sum('amount_final');
                    $program->collected = $collected;
                    if(!empty($program->target_amount)) {
                        $progress = floor(($collected / $program->target_amount) * 100);
                        $program->progress = $progress;
                    }
                    try {
                        $program->save();
                        DB::commit();
                        $link = 'https://semangatbantu.com/p/'.$program->link;
                        $owner = $program->created_by == 1 ? '#SemangatBantu' : $program->owner->name;
                        if(!empty($r->funder_phone)) {
                            $amount = number_format($r->amount_final,0,',','.');

                            /* SEND NOTIF PAYMENT RECEIVED TO WA */
                            $phone = trim($r->funder_phone);
                            $message = '*AAJARAKALLAHU FIIMAA A’THAITA WA BAARAKA LAKA FIIMAA ABQAITA WA JA’ALAHU LAKA THAHUURAN*

"Semoga Allah memberi pahala atas apa yang telah engkau berikan, melimpahkan berkah terhadap hartamu yang tersisa dan menjadikannya penyuci bagimu ".

Terima kasih *'.$r->funder_name.'!* 😊

Donasi Anda sebesar *Rp '.$amount.'* sudah diterima untuk penggalangan dana program *'.$program->name.'* di *'.$link.'* 👈

*Untuk berita penyaluran akan di update pada link di atas ya* 👆

Yuk, bantu *#SemangatBantu* dengan menyebarkan penggalangan ini ke orang-orang terdekat Anda. 🗣️';
                            $this->sayThanksWA($phone,$message);

                            if(!empty($r->funder_email)) {
                                try {
                                    Mail::to($r->funder_email)
                                        ->send(new PaymentThankYou($r));
                                } catch(Exception $e) {
                                    return redirect()->back()->with('error', 'Gagal mengirim E-Mail ke '.$r->funder_email);
                                }
                            }
                        }
                    } catch(Exception $e) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Gagal update data. '.$e->getMessage);
                    }
                }
            }
        }
        return redirect()->route('donasi')->with('success','Update status berhasil');
    }
    public function editDone($id)
    {
        $r = Donasi::findOrFail($id);
        // $channels = PaymentChannel::where([['is_active', 1],['group_type', '=', 'bankreg']])->get();
        $channels = PaymentChannel::where('shown_in_admin', 1)->get();
        $programs = Program::where('published', 1)->get();
        if ($r->payment_channel_type != 'bankreg') {
            return redirect()->back();
        }
        return view('donasi.editDone', compact('r', 'programs', 'channels'));
    }
    public function updateDone(Request $request, $id)
    {
        DB::beginTransaction();
        $r = Donasi::findOrFail($id);
        $channel = PaymentChannel::findOrFail($request->input('payment_channel_id'));
        $r->funder_phone = $request->input('funder_phone');
        $r->funder_email = $request->input('funder_email');
        $r->refcode = $request->input('refcode');
        $r->payment_channel_id = $request->input('payment_channel_id');
        $r->payment_channel_vendor = $channel->vendor;
        $r->payment_channel_type = $channel->group_type;
        $r->payment_channel_code = $channel->code;
        $r->payment_channel_name = $channel->name;
        $r->payment_account_number = $channel->account_number;
        $r->amount_final = $request->input('accepted_amount');
        $r->updated_at = date('Y-m-d H:i:s');
        $r->updated_by = Auth::user()->id;
        $program = Program::findOrFail($r->program_id);
        $old_program_id = $r->program_id;
        $new_program_id = $r->program_id = $request->input('program_id');
        if($program && $r->save()) {
            $collected = Donasi::where([
                ['program_id', '=', $program->id],
                ['status', '=', 'done']
            ])->sum('amount_final');
            $program->collected = $collected;
            if(!empty($program->target_amount)) {
                $progress = floor(($collected / $program->target_amount) * 100);
                $program->progress = $progress;
            }
            if ($new_program_id != $old_program_id) {
                $program_old = Program::findOrFail($old_program_id);
                $program_new = Program::findOrFail($new_program_id);
                $program_new->collected = Donasi::where([
                    ['program_id', '=', $program_new->id],
                    ['status', '=', 'done']
                ])->sum('amount_final');
                $program_old->collected = Donasi::where([
                    ['program_id', '=', $program_old->id],
                    ['status', '=', 'done']
                ])->sum('amount_final');
                if(!empty($program_old->target_amount) && !empty($program_new->target_amount)) {
                    $program_old->progress = floor(($program_old->collected / $program_old->target_amount) * 100);
                    $program_new->progress = floor(($program_new->collected / $program_new->target_amount) * 100);   
                }
                $program_old->save();
                $program_new->save();
            }
            try {
                $program->save();
                DB::commit();
            } catch(Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal update data. '.$e->getMessage);
            }
        }
        return redirect()->route('donasi')->with('success','Berhasil update data');
    }
    public function delete(Request $request) {
        DB::beginTransaction();
        $data = Donasi::findOrFail($request->input('id'));
        $program = Program::findOrfail($data->program_id);
        if($program && $data->delete()) {
            $collected = Donasi::where([
                ['program_id', '=', $program->id],
                ['status', '=', 'done']
            ])->sum('amount_final');
            $program->collected = $collected;
            if(!empty($program->target_amount)) {
                $progress = floor(($collected / $program->target_amount) * 100);
                $program->progress = $progress;
            }
            $program->save();
            DB::commit();
            return redirect()->route('donasi')->with('success','Data Donasi berhasil dihapus');
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }

    /*FUNCTION SEND NOTIF SAY THANKS USERS WA */
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
    public function submitAmal(Request $request) {
        $donasi = Donasi::findOrFail($request->input('donation_id'));
        if($donasi) {
            DB::beginTransaction();
            $data = new Amal;
            $data->transaction_code = $donasi->transaction_code;
            $data->refcode = $donasi->refcode;
            $data->funder_name = $donasi->funder_name;
            $data->funder_ishidden = $donasi->funder_ishidden;
            $data->funder_phone = $donasi->funder_phone;
            $data->funder_email = $donasi->funder_email;
            $data->funder_message = $donasi->funder_message;
            $data->transfer_code = $donasi->transfer_code;
            $data->payment_channel_id = $donasi->payment_channel_id;
            $data->payment_channel_type = $donasi->payment_channel_type;
            $data->payment_channel_code = $donasi->payment_channel_code;
            $data->payment_channel_name = $donasi->payment_channel_name;
            $data->payment_account_number = $donasi->payment_account_number;
            $data->amount = $donasi->amount;
            $data->amount_final = $request->input('amount_transfered');
            $data->payment_initiated = $donasi->payment_initiated;
            $data->payment_confirmed = $donasi->payment_confirmed;
            $data->attachment = $donasi->attachment;
            $data->payment_finished = date('Y-m-d H:i:s');
            $data->payment_expired = $donasi->payment_expired;
            $data->status = 'done';
            $data->created_at = $donasi->created_at;
            $data->updated_at = date('Y-m-d H:i:s');
            $data->updated_by = Auth::user()->id;
            $data->approved_by = Auth::user()->id;
            try {
                $data->save();
                $donasi->payment_finished = date('Y-m-d H:i:s');
                $donasi->status = 'moved';
                $donasi->updated_at = date('Y-m-d H:i:s');
                $donasi->updated_by = Auth::user()->id;
                $donasi->approved_by = Auth::user()->id;
                $donasi->save();
                DB::commit();
                $amount_transfered = number_format($data->amount_final,0,',','.');
                $amount_shouldbe = number_format($donasi->amount_final,0,',','.');
                $phone = trim($donasi->funder_phone);
                $message = '*AAJARAKALLAHU FIIMAA A’THAITA WA BAARAKA LAKA FIIMAA ABQAITA WA JA’ALAHU LAKA THAHUURAN*
    
"Semoga Allah memberi pahala atas apa yang telah engkau berikan, melimpahkan berkah terhadap hartamu yang tersisa dan menjadikannya penyuci bagimu ".

Terima kasih *'.$donasi->funder_name.'!* 😊

Kami telah menerima donasi dari Anda pada rekening bank kami sebesar *Rp '.$amount_transfered.'* 🙏

Namun dikarenakan donasi yang anda kirimkan tidak sesuai dengan jumlah donasi sebesar *Rp '.$amount_shouldbe.'* yang tertera pada halaman pembayaran, maka donasi tersebut akan kami tampung pada *Kotak Amal* kami 🗳️

Donasi yang masuk pada *Kotak Amal*, akan tetap kami salurkan kepada program yang membutuhkan dengan mengutamakan prinsip *tepat guna, tepat sasaran, dan tepat waktu*. 🤝

Untuk pelaporan penggunaan donasi, nantinya bisa anda pantau melalui laman *Kotak Amal* kami 📝

Yuk, bantu *#SemangatBantu* dengan menyebarkan info program-program yang ada ke orang-orang terdekat Anda melalui situs www.semangatbantu.com 🗣️';
                $this->sayThanksWA($phone,$message);
                return redirect()->route('donasi')->with('success','Update status berhasil');
            } catch(Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal update data. '.$e->getMessage);
            }
        } else {
            return redirect()->back()->with('error', 'Data donasi tidak ditemukan');
        }
    }
    public function sendMail() {
        $r = Donasi::findOrFail(258);
        Mail::to('enamseptember@gmail.com')->send(new PaymentThankYou($r));
    }

    public function getCollected(Request $request)
    {
        $programId = $request->input('program_id');
        $from = date('Y-m-d', strtotime($request->input('start_date')));
        $to = date('Y-m-d', strtotime($request->input('end_date')));
        
        $amount = Donasi::whereBetween('payment_finished', [$from, $to])->where([
                        ['program_id','=', $programId],
                        ['status','=','done']
                    ])->get()->sum('amount_final');

        echo $amount;
    
    }

    public function filter(Request $request) {
        $validator = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required'
        ]);
        $rows = Donasi::where('status', $request->input('status'))
            ->whereBetween('payment_initiated', [$request->input('start_date'), $request->input('end_date')])
            ->get();
        $html = '<table id="datatable" class="table table-bordered table-sm table-hover table-striped" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No. Pembayaran</th><th>Fundraiser</th><th>Tgl. Invoice</th><th>Tgl. Bayar</th><th>Program</th><th>Nama</th><th>Jumlah</th><th>Bayar via</th><th>Status</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>';
        foreach($rows as $r) {
            $amount = !empty($r->amount_final) ? number_format($r->amount_final,0,',','.') : '&infin;';
            $status = $r->status == 'pending' ? ' badge-secondary' : ($r->status == 'done' ? ' badge-success' : ' badge-warning');
            $payInit = !empty($r->payment_initiated) ? date('Y/m/d', strtotime($r->payment_initiated)) : '';
            $payFinish = !empty($r->payment_finished) ? date('Y/m/d', strtotime($r->payment_finished)) : '';
            $fundraiser = !empty($r->refcode) ? $r->fundraiser->name : 'N/A';
            $html .= '<tr>
                <td>'.$r->transaction_code.'</td>
                <td>'.$fundraiser.'</td>
                <td>'.$payInit.'</td>
                <td>'.$payFinish.'</td>
                <td>'.$r->program->name.'</td>
                <td>'.$r->funder_name.'</td>
                <td class="text-right">'.$amount.'</td>
                <td class="text-center">'.$r->payment_channel_name.'</td>
                <td class="text-center"><span class="badge '.$status.'">'.$r->status.'</span></td>';
            
            if ($r->payment_channel_type == 'bankreg' && $r->status == 'done') {
                $html .= '<td>
                    <a href="'.url('/donasi/'.$r->id.'/edit').'" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i>&nbsp;Edit
                    </a>
                </td>';
            } else {
                $html .= '<td>
                    <a href="'.url('/donasi/'.$r->id.'/detail').'" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i>&nbsp;Detail
                    </a>
                </td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        return response()->json(['success' => true, 'html' => $html]);
    }
    public function fundraiser(Request $req) {
        $rows = array();
        $fundraisers = Donasi::select('refcode')->where('status','done')->whereNotNull('refcode')->distinct()->get();
        foreach($fundraisers as $f) {
            $programs = Donasi::select('program_id')
                ->where([
                    ['status','done'],
                    ['refcode',$f->refcode]
                ])->distinct()->get();
            $donaturs = Donasi::where([
                ['status','done'],
                ['refcode',$f->refcode]
            ])->count();
            $total = Donasi::where([
                ['status','done'],
                ['refcode',$f->refcode]
            ])->sum('amount_final');
            array_push($rows, [
                'refcode' => $f->refcode,
                'name' => $f->fundraiser->name,
                'totalProgram' => $programs->count(),
                'total' => $total,
                'donaturs' => $donaturs
            ]);
        }
        $rows = collect($rows)->sortByDesc('total');
        if ($req->ajax()) {
            $refcode = $req->input('refcode');
            $fundraiser = Mitra::where('refcode','=',$refcode)->first();
            $programs = Donasi::select('program_id', 'refcode')->selectRaw('COUNT(*) AS total_donatur')->where([['refcode', '=', $refcode],['status', '=', 'done']])->groupBy('program_id', 'refcode')->get();
            $view = view('donasi.cardDetailFund', compact('programs','fundraiser'))->render();
            return response()->json(['html' => $view, 'success' => 'success']);
        }
        // $rows = json_encode($rows);
        // $fundraisers = Donasi::where('status','done')
        //     ->whereNotNull('refcode')
        //     ->groupBy('refcode')->get();
        // foreach()
        // $total = $rows->sum('amount_final');
        // $total = 0; 
        return view('donasi.fundraiser', compact('rows'));
    }
    public function indexDoa() {
        $programs = Program::all();
        return view('donasi.doa.index', compact('programs'));
    }
    public function filterDoa(Request $request) {
        $validator = $request->validate([
            'program_id' => 'required'
        ]);
        $rows = Donasi::where([
            ['program_id', $request->input('program_id')],
            ['status', 'done']
        ])->whereNotNull('funder_message')->get();
        $html = '<table id="datatable" class="table table-bordered table-sm table-hover table-striped" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Tgl. Bayar</th><th>Donatur</th><th>Telepon</th><th>E-Mail</th><th>Pesan / Doa</th><th>Balasan</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>';
        foreach($rows as $r) {
            $amount = !empty($r->amount_final) ? number_format($r->amount_final,0,',','.') : '&infin;';
            $status = $r->status == 'pending' ? ' badge-secondary' : ($r->status == 'done' ? ' badge-success' : ' badge-warning');
            $payInit = !empty($r->payment_initiated) ? date('Y/m/d', strtotime($r->payment_initiated)) : '';
            $payFinish = !empty($r->payment_finished) ? date('Y/m/d', strtotime($r->payment_finished)) : '';
            $fundraiser = !empty($r->refcode) ? $r->fundraiser->name : 'N/A';
            $html .= '<tr>
                <td>'.$payFinish.'</td>
                <td>'.$r->funder_name.'</td>
                <td>'.$r->funder_phone.'</td>
                <td>'.$r->funder_email.'</td>
                <td>'.$r->funder_message.'</td>
                <td>'.$r->reply->message.'</td>
                <td>
                    <a href="'.url('/donasi/doa/'.$r->id.'/detail').'" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i>&nbsp;Detail
                    </a>
                </td>
            </tr>';
        }
        $html .= '</tbody></table>';
        return response()->json(['success' => true, 'html' => $html]);
    }
    public function detailDoa($id) {
        $r = Donasi::findOrFail($id);
        $dr = DonasiReplies::where('donation_id', $id)->first();
        return view('donasi.doa.detail', compact('r','dr'));
    }
    public function saveReply(Request $request, $id) {
        $reply_id = $request->input('id');
        if($reply_id == '') {
            $data = new DonasiReplies;
            $data->donation_id = $id;
            $data->message = $request->input('message');
            $data->is_published = $request->input('is_published');
            $data->created_at = date('Y-m-d H:i:s');
            $data->created_by = Auth::user()->id;
        } else {
            $data = DonasiReplies::findOrFail($reply_id);
            $data->message = $request->input('message');
            $data->is_published = $request->input('is_published');
            $data->updated_at = date('Y-m-d H:i:s');
            $data->updated_by = Auth::user()->id;
        }
        try {
            $data->save();
            $donation = Donasi::find($id);
            $phone = trim($donation->funder_phone);
            $doa = $donation->funder_message;
            $message = 'Assalamu\'alaikum #KawanBantu. Terima kasih atas donasi Anda pada program:
'.$donation->program->name.'.

*Doa Anda*:
_"'.$doa.'"_
            
*'.$data->message.'*';
            if($data->is_published == 1 && !empty($phone)) $this->sayThanksWA($phone, $message);
        } catch(Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data. '.$e->getMessage);
        }
        return redirect()->route('doa.donasi')->with('success','Berhasil menyimpan data');
    }
}

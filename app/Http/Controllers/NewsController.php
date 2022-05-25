<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\News;
use App\Models\Program;
use App\Models\NewsDetail;
use Exception;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Donasi;

class NewsController extends Controller
{
    //

    public function index()
    {
        $rows = News::all();
        return view('news.index', compact('rows'));
    }

    public function create()
    {
        $programs = Program::all();
        $category = Master::where('datagroup','=','kategori_penyaluran')->get();
        
        return view('news.create', compact('programs', 'category'));
    }

    public function insert(Request $request)

    {
        if ($request->input('program_id') && $request->input('program_id') != '') {  

            $validator = Validator::make($request->all(),[
                'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
                'seo_title' => 'required|unique:news'
            ]);

            if ($validator->fails()) {
                session()->flash('error', 'Gagal upload Gambar');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();
            $r = new News;
            $r->title = $request->input('title');
            $r->seo_title = Str::slug($request->input('seo_title'), '-');
            $r->master_id = 1;
            $r->description = $request->input('description');
            $r->program_id = $request->input('program_id');
            $r->mitra_id = $request->input('mitra_id');
            $r->total_distributed = $request->input('total_distributed');
            $r->date_distributed = date('Y-m-d H:i:s', strtotime($request->input('date_distributed')));
            $r->published = $request->input('published');
            $r->created_at = date('Y-m-d H:i:s');
            $r->updated_at = date('Y-m-d H:i:s');
            $r->updated_by = Auth::user()->id;
            $r->created_by = Auth::user()->id;
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                // $extension = $image->getClientOriginalExtension();
                // $file = Str::slug($r->title, '-');
                $new_img_name = 'NEWS-'.date('YmdHis').'.webp';
                $image->move(public_path('img/photo-berita'), $new_img_name);
                $r->photo = $new_img_name;
            }
            try {
                $r->save();
                $amount = $request->input('amount');
                foreach ($request->input('category_id') as $index => $category_id) {
                    $n = new NewsDetail;
                    if (!empty($amount[$index])) {
                        $n->news_id = $r->id;
                        $n->amount = $amount[$index];
                        $n->category_id = $category_id;
                        $n->save();
                    }
                }
                DB::commit();

                $news = News::find($r->id);
                if($news && $news->count() > 0) {
                    // Send info via WA to donatur
                    if($news->published == 1) {
                        $donatur = Donasi::select('funder_phone')->distinct()->where([
                            ['program_id', $news->program_id],
                            ['status', 'done']
                        ])->get();
                        if($donatur && $donatur->count() > 0) {
                            foreach($donatur as $d) {
                                if(!empty($d->funder_phone) && strlen($d->funder_phone) >= 10) {
                                    $this->sendNotifWA($d, $news);
                                }
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                DB::rollback();
                session()->flash('error', 'Gagal insert data '.$e->getMessage());
                return redirect()->back();
            }
            session()->flash('success', 'Berhasil input berita');
            return redirect()->route('news');
            // return $request;
        }
    }

    public function edit($id)
    {
        $programs = Program::where('published', 1)->get();
        $r = News::find($id);
        $category = NewsDetail::where('news_id', $r->id)->get();
        return view('news.edit', compact('r', 'programs','category'));
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'seo_title' => 'required|unique:news'
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Gagal upload Gambar');
            return redirect()->back()->withErrors($validator)->withInput();
            // return $validator;
        }
        DB::beginTransaction();
        $r = News::findOrFail($id);
        if ($r) {
            $r->title = $request->input('title');
            $r->seo_title = Str::slug($request->input('seo_title'), '-');
            $r->description = $request->input('description');
            $r->program_id = $request->input('program_id');
            $r->total_distributed = $request->input('total_distributed');
            $r->date_distributed = date('Y-m-d H:i:s', strtotime($request->input('date_distributed')));
            $r->published = $request->input('published');
            $r->updated_at = date('Y-m-d H:i:s');
            $r->updated_by = Auth::user()->id;
            if ($request->hasFile('photo')) {
                $old_img_name = public_path('img/photo-berita/'.$r->photo);
                File::delete($old_img_name);
                $image = $request->file('photo');
                // $extension = $image->getClientOriginalExtension();
                $new_img_name = 'NEWS-'.date('YmdHis').'.webp';
                $image->move(public_path('img/photo-berita'), $new_img_name);
                $r->photo = $new_img_name;
            }
            try {
                $r->save();
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                session()->flash('error', 'Gagal update berita'.$e->getMessage());
                return redirect()->back();
            }
            session()->flash('success', 'Berhasil update berita');
            return redirect()->route('news');      
        }
    }
    function sendNotifWA($donasi, $berita) {
        $phone = $donasi->funder_phone;
        $fronturl = 'https://semangatbantu.com/';
        $program = $berita->program->name;
        $landingurl = !empty($berita->program->seo_link) ? $berita->program->seo_link : 'p/'.$berita->program->link;
        // $link = $fronturl.$landingurl.'/berita-'.$berita->id;
        $link = !empty($berita->seo_title) ? $fronturl.$landingurl.'/berita/'.$berita->seo_title : $fronturl.$landingurl.'/berita-'.$berita->id;

        $curl = curl_init();
        $token = env('WABLAS_TOKEN');
        $message = '*Assalamu\'alaikum Kawan Bantu* 😊

"Alhamdulillah donasi yang disalurkan untuk program '.$program.' telah disalurkan kepada penerima manfaat yang membutuhkan" 🤲
        
Untuk laporannya bisa di cek di link '.$link.' 👈 
        
Untuk kegiatan-kegiatan dari Semangatbantu lainnya bisa di cek melalui https://www.instagram.com/semangatbantu/ dan https://www.youtube.com/channel/UCk6dRv6Ff8cYdeGes0vsTJQ 👈
        
Yuk, bantu SemangatBantu dengan menyebarkan penggalangan ini '.$fronturl.$landingurl.' ke orang-orang terdekat Anda. 🗣️

*Mari Berdonasi kembali untuk membantu program '.$program.' melalui link '.$fronturl.$landingurl.'

Semoga menjadi ladang amal jariyah pemberat timbangan kebaikan Kawan Bantu kelak. Aamiin*';

        $data = [
            'phone' => $phone,
            'message' => $message
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, env('WABLAS_HOST')."/api/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
    }
}

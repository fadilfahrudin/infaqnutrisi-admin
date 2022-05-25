<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Program;
use App\Models\Mitra;
use Auth;

class ProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $rows = Program::all();
        return view('program.index', compact('rows'));
    }
    public function create() {
        $program = Program::where('published', 1)->get();
        $owners = Mitra::all();
        return view('program.create', compact('program','owners'));
    }
    public function store(Request $request) {
        $r = new Program;
        $is_published = $request->input('published');
        $r->category_id = 1;
        $r->name = $request->input('name');
        $insert_after = $request->input('insert_after');
        $urutan = empty($insert_after) ? 1 : $insert_after + 1;
        $r->urutan = $urutan;
        $slug_judul = Str::slug($request->input('name'), '-');
        $r->link = $slug_judul;
        $r->seo_link = $request->input('seo_link');
        if($request->input('expired_date')) $r->expired_date = $request->input('expired_date');
        $r->target_amount = $request->input('target_amount');
        $r->pitch = $request->input('pitch');
        $r->description = $request->input('description');
        $r->photo = 'program/'.$slug_judul.'.webp';
        if($request->input('featured')) $r->banner_photo = 'program/banner-'.$slug_judul.'.webp';
        $r->published = $request->input('published') ? 1 : 0;
        $placements = $request->input('placement');
        if(!empty($placements)) {
            if(count($placements) > 1) {
                $r->placement = implode(",",$placements);
            } else {
                $r->placement = $placements[0];
            }
        }
        $r->featured = $request->input('featured') ? 1 : 0;
        if($is_published) {
            $r->verified = 1;
            $r->verified_by = Auth::user()->id;
        }
        $r->published_by = $request->input('published_by');
        $r->created_at = date('Y-m-d H:i:s');
        $r->created_by = $request->input('created_by');
        $r->save();
        return redirect()->route('program')->with(['success','Berhasil menyimpan data']);
    }
    public function edit($id) {
        $r = Program::findOrFail($id);
        $program = Program::where('published', 1)->get();
        $owners = Mitra::all();
        return view('program.edit', compact('r','program','owners'));
    }
    public function update(Request $request, $id) {
        $r = Program::findOrFail($id);
        if($request->input('name') != '') $r->name = $request->input('name');
        if($request->input('pitch') != '') $r->pitch = $request->input('pitch');
        if($request->input('seo_link') != '') $r->seo_link = $request->input('seo_link');
        if($request->input('expired_date') != '') $r->expired_date = date('Y-m-d', strtotime($request->input('expired_date')));
        if($request->input('target_amount') != '') $r->target_amount = $request->input('target_amount');
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $data_img = 'cover_'.date('YmdHis').'.webp';
            $new_img_name = asset('img/program/'.$data_img);
            $image->move(public_path('img/program'), $data_img);
            $r->photo = $new_img_name;
        }
        if ($request->hasFile('banner_photo')) {
            $image_banner = $request->file('banner_photo');
            $data_img_banner = 'banner_'.date('YmdHis').'.webp';
            $new_img_banner_name = asset('img/program/'.$data_img_banner);
            $image_banner->move(public_path('img/program'), $data_img_banner);
            $r->banner_photo = $new_img_banner_name;
        }
        $insert_after = $request->input('insert_after');
        $urutan = empty($insert_after) ? 1 : $insert_after + 1;
        $r->urutan = $urutan;
        $r->description = $request->input('description');
        $placements = $request->input('placement');
        if(!empty($placements)) {
            if(count($placements) > 1) {
                $r->placement = implode(",",$placements);
            } else {
                $r->placement = $placements[0];
            }
        }
        $r->published_by = $request->input('published_by');
        $r->created_by = $request->input('created_by');
        $r->save();
        return redirect()->route('program')->with('success', 'Berhasil update data');
    }
}

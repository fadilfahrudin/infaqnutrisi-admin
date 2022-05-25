<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\Program;
use App\Models\Mitra;
use DB;
use App\Models\PaymentChannel;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $programs = Program::where('published', 1)->get();
        $collected = Donasi::where('status', 'done')->sum('amount_final');
        $mitra = Mitra::where('is_verified', 1)->get();
        // $donasi = Donasi::where('status', 'done')->get();
        $donatur = Donasi::distinct('funder_phone')->where('status', 'done')->get();
        $channels = PaymentChannel::where('shown_in_admin', 1)->get();
        $rekap = array();
        $sums = array();
        $total_per_channel = array();
        foreach($programs as $p) {
            $total = Donasi::where([
                ['program_id', '=', $p->id],
                ['status', '=', 'done']
            ])->sum('amount_final');
            foreach($channels as $c) {
                $terkumpul = Donasi::where([
                    ['program_id', '=', $p->id],
                    ['payment_channel_id', '=', $c->id],
                    ['status', '=', 'done']
                ])->sum('amount_final');
                $sums[$c->id] = $terkumpul;
            }
            array_push($rekap, array(
                'id'    => $p->id,
                'name'  => $p->name,
                'sums'  => $sums,
                'total' => $total
            ));
        }
        foreach($channels as $c) {
            $terkumpul = Donasi::where([
                ['payment_channel_id', '=', $c->id],
                ['status', '=', 'done']
            ])->sum('amount_final');
            $total_per_channel[$c->id] = $terkumpul;
        }
        // $donatur = DB::table('donations')->select('distinct funder_phone')->where('status', 'done')->get();
        return view('dashboard', compact('programs','collected','mitra','donatur','channels','rekap','total_per_channel'));
    }
}

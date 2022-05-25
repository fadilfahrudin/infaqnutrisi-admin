<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Donasi;
use App\Models\Amal;
use DateTime;
use DatePeriod;
use DateInterval;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function index() {
        return view('report.index');
    }
    public function preview(Request $request) {
        $validator = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required'
        ]);
        $return = array();
        $programs = Program::select('id', 'name')->where('published', 1)->get();
        $start = new DateTime($request->input('start_date'));
        $end = new DateTime($request->input('end_date'));
        $end = $end->modify( '+1 day' );
        $daterange = new DatePeriod($start, new DateInterval('P1D'), $end);
        $html = '<table class="table table-sm table-striped" width="100%"><thead><tr><th rowspan="2">Tanggal</th><th rowspan="2">Bulan</th><th rowspan="2">Hari</th><th colspan="'.count($programs).'">Nama Program</th><th rowspan="2">Kotak Amal</th><th rowspan="2">Jumlah</th></tr><tr>';
        foreach($programs as $p) {
            $html .= '<th>'.$p->name.'</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach($daterange as $date) {
            $html .= '<tr><td class="text-right">'.$date->format("j").'</td><td>'.$date->format("M").'</td><td>'.$date->format("D").'</td>';
            $amal = $subtotal = 0;
            foreach($programs as $p) {
                $terkumpul = 0;
                if($request->input('status') == 'done' || $request->input('status') == 'moved') {
                    $terkumpul = Donasi::where([
                            ['status', '=', $request->input('status')],
                            ['program_id', '=', $p->id]
                        ])->whereRaw('DATE(payment_finished) = ?', $date->format("Y-m-d"))->sum('amount_final');
                } else {
                    $terkumpul = Donasi::where([
                            ['status', '=', $request->input('status')],
                            ['program_id', '=', $p->id]
                        ])->whereRaw('DATE(payment_initiated) = ?', $date->format("Y-m-d"))->sum('amount_final');

                }
                $html .= '<td class="text-right">'.number_format($terkumpul,0,',','.').'</td>';
            }
            if($request->input('status') == 'done' || $request->input('status') == 'moved') {
                $subtotal = Donasi::where('status', $request->input('status'))->whereRaw('DATE(payment_finished) = ?', $date->format("Y-m-d"))->sum('amount_final');
            } else {
                $subtotal = Donasi::where('status', $request->input('status'))->whereRaw('DATE(payment_initiated) = ?', $date->format("Y-m-d"))->sum('amount_final');
            }
            if($request->input('status') == 'done' || $request->input('status') == 'moved') {
                $amal = Amal::where('status', $request->input('status'))->whereRaw('DATE(payment_finished) = ?', $date->format("Y-m-d"))->sum('amount_final');
            } else {
                $amal = Amal::where('status', $request->input('status'))->whereRaw('DATE(payment_initiated) = ?', $date->format("Y-m-d"))->sum('amount_final');
            }
            $html .= '<td class="text-right">'.number_format($amal,0,',','.').'</td><td class="text-right">'.number_format($subtotal,0,',','.').'</td></tr>';
        }
        $html .= '</tbody></table>';
        // return response()->json(['success' => true, 'program' => $programs, 'rows' => $return]);
        return response()->json(['success' => true, 'program' => $programs, 'html' => $html]);
    }
    public function export(Request $request) {
        $validator = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required'
        ]);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $from = date('d M Y', strtotime($request->input('start_date')));
        $to = date('d M Y', strtotime($request->input('end_date')));
        $status = $request->input('status');
        $programs = Program::select('id', 'name')->where('published', 1)->get();
        $start = new DateTime($request->input('start_date'));
        $end = new DateTime($request->input('end_date'));
        $end = $end->modify( '+1 day' );
        $daterange = new DatePeriod($start, new DateInterval('P1D'), $end);
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        /* Setup Headers */
        $sheet->setTitle('MONEV HARIAN PER PROGRAM');
        $sheet->setCellValue('A1', 'MONEV HARIAN PER PROGRAM');
        $sheet->setCellValue('A2', 'PERIODE '.$from.' S/D '.$to);
        $sheet->setCellValue('A4', 'Tgl');
        $sheet->setCellValue('B4', 'Bln');
        $sheet->setCellValue('C4', 'Hari');
        $sheet->setCellValue('D4', 'NAMA PROGRAM');
        $sheet->mergeCells('A4:A5');
        $sheet->mergeCells('B4:B5');
        $sheet->mergeCells('C4:C5');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $initCol = 'C';
        foreach($programs as $p) {
            $initCol++;
            $sheet->setCellValue($initCol.'5', $p->name);
            $sheet->getColumnDimension($initCol)->setWidth(15);
        }
        $endCol = $initCol;
        $sheet->mergeCells('D4:'.$initCol.'4');
        $endCol++;
        $sheet->setCellValue($endCol.'4', 'Kotak Amal');
        $sheet->mergeCells($endCol.'4:'.$endCol.'5');
        $sheet->getColumnDimension($endCol)->setAutoSize(true);
        $endCol++;
        $sheet->setCellValue($endCol.'4', 'Jumlah');
        $sheet->mergeCells($endCol.'4:'.$endCol.'5');
        $sheet->getColumnDimension($endCol)->setAutoSize(true);
        $sheet->mergeCells('A1:'.$endCol.'1');
        $sheet->mergeCells('A2:'.$endCol.'2');
        $sheet->getStyle('A1:'.$endCol.'5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D5:'.$initCol.'5')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:'.$endCol.'5')->getFont()->setBold(true);
        $sheet->getStyle('A4:'.$endCol.'5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5BD40B');
        $sheet->getStyle('A4:'.$endCol.'5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        /* End of Setup Headers */

        /* Start Contents */
        $startRow = 5; $dataCol = 'C';
        foreach($daterange as $date) {
            $startRow++;
            $sheet->setCellValue('A'.$startRow, $date->format('j'));
            $sheet->setCellValue('B'.$startRow, $date->format('M'));
            $sheet->setCellValue('C'.$startRow, $date->format('D'));
            $sheet->getStyle('A'.$startRow.':C'.$startRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $amal = $subtotal = 0;
            foreach($programs as $p) {
                $dataCol++;
                $terkumpul = 0;
                if($request->input('status') == 'done' || $request->input('status') == 'moved') {
                    $terkumpul = Donasi::where([
                            ['status', '=', $request->input('status')],
                            ['program_id', '=', $p->id]
                        ])->whereRaw('DATE(payment_finished) = ?', $date->format("Y-m-d"))->sum('amount_final');
                } else {
                    $terkumpul = Donasi::where([
                            ['status', '=', $request->input('status')],
                            ['program_id', '=', $p->id]
                        ])->whereRaw('DATE(payment_initiated) = ?', $date->format("Y-m-d"))->sum('amount_final');
                }
                $sheet->setCellValue($dataCol.$startRow, $terkumpul);
                $sheet->getStyle($dataCol.$startRow)->getNumberFormat()->setFormatCode('#,##0');
            }
            if($request->input('status') == 'done' || $request->input('status') == 'moved') {
                $amal = Amal::where('status', $request->input('status'))->whereRaw('DATE(payment_finished) = ?', $date->format("Y-m-d"))->sum('amount_final');
            } else {
                $amal = Amal::where('status', $request->input('status'))->whereRaw('DATE(payment_initiated) = ?', $date->format("Y-m-d"))->sum('amount_final');
            }
            $dataCol++;
            $sheet->setCellValue($dataCol.$startRow, $amal);
            $sheet->getStyle($dataCol.$startRow)->getNumberFormat()->setFormatCode('#,##0');

            if($request->input('status') == 'done' || $request->input('status') == 'moved') {
                $subtotal = Donasi::where('status', $request->input('status'))->whereRaw('DATE(payment_finished) = ?', $date->format("Y-m-d"))->sum('amount_final');
            } else {
                $subtotal = Donasi::where('status', $request->input('status'))->whereRaw('DATE(payment_initiated) = ?', $date->format("Y-m-d"))->sum('amount_final');
            }
            $dataCol++;
            $sheet->setCellValue($dataCol.$startRow, $subtotal);
            $sheet->getStyle($dataCol.$startRow)->getNumberFormat()->setFormatCode('#,##0');
            $dataCol = 'C';
        }
        /* End of Contents */

        /* Footer */
        $startRow++;
        $sheet->setCellValue('A'.$startRow, 'JUMLAH');
        $sheet->mergeCells('A'.$startRow.':C'.$startRow);
        $sheet->getStyle('A'.$startRow.':C'.$startRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $footerCol = 'C';
        foreach($programs as $p) {
            $footerCol++;
            $programTotal = 0;
            if($request->input('status') == 'done' || $request->input('status') == 'moved') {
                $programTotal = Donasi::where([
                        ['status', '=', $request->input('status')],
                        ['program_id', '=', $p->id]
                    ])->whereRaw("DATE(payment_finished) BETWEEN '".date('Y-m-d', strtotime($request->input('start_date')))."' AND '".date('Y-m-d', strtotime($request->input('end_date')))."'")->sum('amount_final');
            } else {
                $programTotal = Donasi::where([
                    ['status', '=', $request->input('status')],
                    ['program_id', '=', $p->id]
                ])->whereRaw("DATE(payment_initiated) BETWEEN '".date('Y-m-d', strtotime($request->input('start_date')))."' AND '".date('Y-m-d', strtotime($request->input('end_date')))."'")->sum('amount_final');
            }
            $sheet->setCellValue($footerCol.$startRow, $programTotal);
            $sheet->getStyle($footerCol.$startRow)->getNumberFormat()->setFormatCode('#,##0');
        }
        $footerCol++;
        $totalAmal = 0;
        if($request->input('status') == 'done' || $request->input('status') == 'moved') {
            $totalAmal = Amal::where('status', $request->input('status'))->whereRaw("DATE(payment_finished) BETWEEN '".date('Y-m-d', strtotime($request->input('start_date')))."' AND '".date('Y-m-d', strtotime($request->input('end_date')))."'")->sum('amount_final');
        } else {
            $totalAmal = Amal::where('status', $request->input('status'))->whereRaw("DATE(payment_initiated) BETWEEN '".date('Y-m-d', strtotime($request->input('start_date')))."' AND '".date('Y-m-d', strtotime($request->input('end_date')))."'")->sum('amount_final');
        }
        $sheet->setCellValue($footerCol.$startRow, $totalAmal);
        $sheet->getStyle($footerCol.$startRow)->getNumberFormat()->setFormatCode('#,##0');
        $footerCol++;
        $grandTotal = 0;
        if($request->input('status') == 'done' || $request->input('status') == 'moved') {
            $grandTotal = Donasi::where('status', $request->input('status'))->whereRaw("DATE(payment_finished) BETWEEN '".date('Y-m-d', strtotime($request->input('start_date')))."' AND '".date('Y-m-d', strtotime($request->input('end_date')))."'")->sum('amount_final');
        } else {
            $grandTotal = Donasi::where('status', $request->input('status'))->whereRaw("DATE(payment_initiated) BETWEEN '".date('Y-m-d', strtotime($request->input('start_date')))."' AND '".date('Y-m-d', strtotime($request->input('end_date')))."'")->sum('amount_final');
        }
        $sheet->setCellValue($footerCol.$startRow, $grandTotal);
        $sheet->getStyle($footerCol.$startRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A'.$startRow.':'.$footerCol.$startRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5BD40B');
        /* End of Footer */
        $sheet->getStyle('A4:'.$footerCol.$startRow)->applyFromArray($styleArray);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="LAPORAN_MONEV_HARIAN.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function preview_old(Request $request) {
        $validator = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required'
        ]);
        $return = array();
        $programs = Program::select('id', 'name')->where('published', 1)->get();
        $start = new DateTime($request->input('start_date'));
        $end = new DateTime($request->input('end_date'));
        $daterange = new DatePeriod($start, new DateInterval('P1D'), $end);
        // foreach($programs as $p) {
        //     array_push($return['program'], [
        //         'id' => $p->id,
        //         'nama' => $p->name
        //     ]);
        //     // $return['program'] = [
        //     //     'id' => $p->id,
        //     //     'nama' => $p->name
        //     // ];
        // }
        foreach($daterange as $date) {
            $rows = array(
                'tgl' => $date->format("j"),
                'bln' => $date->format("M"),
                'hari' => $date->format("D")
            );
            $rows['collected'] = array();
            foreach($programs as $p) {
                $terkumpul = 0;
                if($request->input('status') == 'done' || $request->input('status') == 'moved') {
                    $terkumpul = Donasi::where([
                            ['status', '=', $request->input('status')],
                            ['program_id', '=', $p->id],
                            ['payment_finished', '=', $date->format("Y-m-d")]
                        ])->sum('amount_final');
                } else {
                    $terkumpul = Donasi::where([
                            ['status', '=', $request->input('status')],
                            ['program_id', '=', $p->id],
                            ['payment_initiated', '=', $date->format("Y-m-d")]
                        ])->sum('amount_final');

                }
                array_push($rows['collected'],[
                    'id' => $p->id,
                    'terkumpul' => $terkumpul
                ]);
            }
            array_push($return, $rows);
            // $return['rows'] = $rows;
        }
        return response()->json(['success' => true, 'program' => $programs, 'rows' => $return]);
    }
}
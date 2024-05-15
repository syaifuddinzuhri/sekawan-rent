<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Services\LaporanService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new LaporanService();
    }

    public function index()
    {
        return view('laporan.index');
    }

    public function getReport(Request $request)
    {
        try {
            $data = $this->service->getReport($request);
            $from = $request->from;
            $to = $request->to;
            return view('laporan.preview', compact('data', 'from', 'to'));
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $data = $this->service->getReport($request);
            activity()
                ->withProperties([
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Success Data report export');
            return Excel::download(new ReportExport($data), 'Laporan Pemesanan Kendaraan ' . $request->from . ' - ' . $request->to . '.xlsx');
        } catch (\Throwable $th) {
            activity()
                ->withProperties([
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Error Data export report');
            return redirect()->back();
        }
    }
}

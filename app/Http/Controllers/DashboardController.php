<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new DashboardService();
    }

    public function index()
    {
        return view('dashboard.index');
    }

    public function getReport(Request $request)
    {
        try {
            $data = $this->service->getReport($request);
            return response()->success($data, 'OKE');
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        }
    }
}

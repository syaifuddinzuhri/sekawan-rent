<?php

namespace App\Http\Controllers;

use App\Constants\GlobalConstant;
use App\Http\Requests\PemesananRequest;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\PemesananService;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new PemesananService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->service->datatables($request);
        }
        return view('pemesanan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $driver = User::select('id', 'name')->where('role', GlobalConstant::ROLE_DRIVER)->get();
        $approver = User::select('id', 'name')->where('role', GlobalConstant::ROLE_APPROVER)->get();
        return view('pemesanan.form', compact('approver', 'driver'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PemesananRequest $request)
    {
        try {
            $data = $this->service->store($request);
            return response()->success($data, 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = Booking::with(['approvals.approver', 'driver', 'vehicle']);

        if (authUser()->role == 'approver') {
            $userId = authUser()->id;
            $query->whereHas('approvals', function ($query) use ($userId) {
                $query->where('user_approver_id', $userId);
            });
        }

        $data = $query->find($id);
        if (!$data) return redirect()->route('pemesanan.index');
        return view('pemesanan.detail', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        try {
            $data = $this->service->update($request, $id);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    public function updateApproval(Request $request, $id)
    {
        try {
            $data = $this->service->updateApproval($request, $id);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // $data = $this->service->destory($id);
            // return response()->success($data, 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        }
    }
}

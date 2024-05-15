<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\KendaraanRequest;
use App\Services\KendaraanService;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new KendaraanService();
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
        return view('kendaraan.index');
    }

    public function listKendaraan(Request $request)
    {
        try {
            $data = $this->service->listKendaraan($request);
            return $data;
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route = route('kendaraan.store');
        return view('kendaraan.form', compact('route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KendaraanRequest $request)
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
        try {
            $data = $this->service->getById($id);
            return response()->success($data, 'Data berhasil didapatkan');
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->service->edit($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KendaraanRequest $request, $id)
    {
        try {
            $data = $this->service->update($request, $id);
            return response()->success($data, 'Data berhasil diubah');
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
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
            $data = $this->service->destory($id);
            return response()->success($data, 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        }
    }
}

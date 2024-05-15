<?php

namespace App\Services;

use App\Constants\GlobalConstant;
use App\Models\Vehicle;
use Yajra\DataTables\Facades\DataTables;

class KendaraanService
{

    public function datatables($request)
    {
        try {
            $query = Vehicle::query();
            $data = $query->latest()->get();
            return DataTables::of($data)
                ->setRowAttr([
                    'url' => function ($data) {
                        return route('kendaraan.destroy', $data->id);
                    },
                    'edit_url' => function ($data) {
                        return route('kendaraan.edit', $data->id);
                    },
                ])
                ->addIndexColumn()
                ->editColumn('status', function ($data) {
                    return statusVehicleBadge($data->status);
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="btn-group" role="group">';
                    $button .= '<button type="button" class="btn btn-sm btn-info edit" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></button>';
                    $button .= '<button type="button" data-bs-toggle="modal" data-bs-target="#modal-delete" data-backdrop="static" data-keyboard="false" class="btn btn-sm btn-danger delete" data-toggle="tooltip" data-placement="bottom" title="Hapus"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function create()
    {
        try {
            return view('kendaraan.form');
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function listKendaraan($request)
    {
        try {
            $filter = ['name', 'police_number'];
            $data = Vehicle::where('status', GlobalConstant::STATUS_AVAILABLE)->limit(8)
                ->whereLike($filter, $request->search)
                ->get();
            return view('pemesanan.list', compact('data'));
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function getById($id)
    {
        try {
            return Vehicle::find($id);
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->getById($id);
            $route = route('kendaraan.update', $id);
            return view('kendaraan.form', compact('data', 'route'));
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function update($request, $id)
    {
        try {
            $data = $this->getById($id);
            $oldAttributes = $data->getAttributes();

            $data->update($request->all());

            activity()
                ->performedOn($data)
                ->withProperties([
                    'old_attributes' => $oldAttributes,
                    'new_attributes' => $data->getAttributes(),
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Data kendaraan updated successfully');
            return $data;
        } catch (\Exception $e) {
            activity()
                ->withProperties([
                    'old_attributes' => $oldAttributes ?? '',
                    'new_attributes' => $data->getAttributes() ?? '',
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                    'error' => $e->getMessage()
                ])
                ->log('Error kendaraan updated');
            throw $e;
            report($e);
            return $e;
        }
    }

    public function destory($id)
    {
        try {
            $data = $this->getById($id);
            $data->delete();

            activity()
                ->performedOn($data)
                ->withProperties([
                    'deleted_at' => now()->toDateTimeString(),
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Data kendaraan deleted successfully');
            return $data;
        } catch (\Exception $e) {
            activity()
                ->withProperties([
                    'deleted_at' => now()->toDateTimeString(),
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                    'error' => $e->getMessage(),
                ])
                ->log('Error kendaraan deleted');
            throw $e;
            report($e);
            return $e;
        }
    }

    public function store($request)
    {
        try {
            $data = Vehicle::create($request->all());
            activity()
                ->performedOn($data)
                ->withProperties([
                    'data' => $data,
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Data kendaraan created successfully');
            return $data;
        } catch (\Exception $e) {
            activity()
                ->withProperties([
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                    'error' => $e->getMessage(),
                ])
                ->log('Error kendaraan created');
            throw $e;
            report($e);
            return $e;
        }
    }
}

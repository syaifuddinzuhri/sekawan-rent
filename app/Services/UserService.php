<?php

namespace App\Services;

use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserService
{

    public function datatables($request)
    {
        try {
            $query = User::query();
            if (isset($request->role)) {
                $query->where('role', $request->role);
            }
            $data = $query->latest()->get();
            return DataTables::of($data)
                ->setRowAttr([
                    'url' => function ($data) {
                        return route('user.destroy', $data->id);
                    },
                    'edit_url' => function ($data) {
                        return route('user.edit', $data->id);
                    },
                ])
                ->addIndexColumn()
                ->editColumn('role', function ($data) {
                    return roleBadge($data->role);
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="btn-group" role="group">';
                    $button .= '<button type="button" class="btn btn-sm btn-info edit" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></button>';
                    $button .= '<button type="button" data-bs-toggle="modal" data-bs-target="#modal-delete" data-backdrop="static" data-keyboard="false" class="btn btn-sm btn-danger delete" data-toggle="tooltip" data-placement="bottom" title="Hapus"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['role', 'action'])
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
            return view('user.form');
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function getById($id)
    {
        try {
            return User::find($id);
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
            $route = route('user.update', $id);
            return view('user.form', compact('data', 'route'));
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
                ->log('Data user updated successfully');
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
                ->log('Error user updated');
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
                ->log('Data user deleted successfully');
            return $data;
        } catch (\Exception $e) {
            activity()
                ->withProperties([
                    'deleted_at' => now()->toDateTimeString(),
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                    'error' => $e->getMessage(),
                ])
                ->log('Error user deleted');
            throw $e;
            report($e);
            return $e;
        }
    }

    public function store($request)
    {
        try {
            $data = User::create($request->all());
            activity()
                ->performedOn($data)
                ->withProperties([
                    'data' => $data,
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Data user created successfully');
            return $data;
        } catch (\Exception $e) {
            activity()
                ->withProperties([
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                    'error' => $e->getMessage(),
                ])
                ->log('Error user created');
            throw $e;
            report($e);
            return $e;
        }
    }
}

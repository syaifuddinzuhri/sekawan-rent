<?php

namespace App\Services;

use App\Constants\GlobalConstant;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Vehicle;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PemesananService
{

    public function datatables($request)
    {
        try {
            $query = Booking::with(['approvals.approver', 'vehicle', 'driver']);
            if (authUser()->role == 'approver') {
                $userId = authUser()->id;
                $query->whereHas('approvals', function ($query) use ($userId) {
                    $query->where('user_approver_id', $userId);
                });
            }
            $data = $query->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($data) {
                    return statusBadge($data->status);
                })
                ->editColumn('name', function ($data) {
                    return $data->vehicle ? $data->vehicle->police_number . ' - ' . $data->vehicle->name . ' | ' . $data->vehicle->brand : "";
                })
                ->editColumn('driver', function ($data) {
                    return $data->driver ? $data->driver->name : "";
                })
                ->editColumn('approver', function ($data) {
                    return '<span>' . count($data->approvals) . ' Pihak</span>';
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="btn-group" role="group">';
                    $button .= '<a href="' . route('pemesanan.show', $data->id) . '" class="btn btn-sm btn-info detail" data-toggle="tooltip" data-placement="bottom" title="Detail"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['name', 'status', 'action', 'driver', 'approver'])
                ->make(true);
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            if (!isset($request->user_approver_ids) || count($request->user_approver_ids) < 2) {
                throw new Exception("Pihak menyetujui harus diisi minimal 2");
            }
            $data = Booking::create([
                'vehicle_id' => $request->vehicle_id,
                'user_driver_id' => $request->user_driver_id,
                'date' => $request->date,
                'status' => GlobalConstant::STATUS_PENDING
            ]);
            foreach ($request->user_approver_ids as $key => $value) {
                BookingApproval::create([
                    'booking_id' => $data->id,
                    'user_approver_id' => $value,
                    'status' => GlobalConstant::STATUS_PENDING
                ]);
            }

            Vehicle::where('id', $request->vehicle_id)->update([
                'status' => GlobalConstant::STATUS_NOT_AVAILABLE
            ]);

            activity()
                ->performedOn($data)
                ->withProperties([
                    'data' => $data,
                    'vehicle_id' => $request->vehicle_id,
                    'user_driver_id' => $request->user_driver_id,
                    'date' => $request->date,
                    'status' => GlobalConstant::STATUS_PENDING,
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Booking created successfully');
            DB::commit();

            return $data;
        } catch (\Exception $e) {
            DB::rollback();

            activity()
                ->withProperties([
                    'vehicle_id' => $request->vehicle_id ?? null,
                    'user_driver_id' => $request->user_driver_id ?? null,
                    'date' => $request->date ?? null,
                    'status' => GlobalConstant::STATUS_PENDING,
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                    'error' => $e->getMessage(),
                ])
                ->log('Error occurred while creating booking');
            throw $e;
            report($e);
            return $e;
        }
    }


    public function update($request, $id)
    {
        try {
            DB::beginTransaction();
            $booking = Booking::with(['approvals'])
                ->where(function ($q) {
                    if (authUser()->role == 'approver') {
                        $userId = authUser()->id;
                        $q->whereHas('approvals', function ($query) use ($userId) {
                            $query->where('user_approver_id', $userId);
                        });
                    }
                })
                ->find($id);
            if (!$booking) throw new \Exception('Data tidak ditemukan');
            $booking->update([
                'status' => GlobalConstant::STATUS_CLOSED
            ]);

            $vehilce = Vehicle::find($booking->vehicle_id);
            $vehilce->update([
                'status' => GlobalConstant::STATUS_AVAILABLE
            ]);
            activity()
                ->performedOn($booking)
                ->withProperties([
                    'data' => $booking,
                    'status' => GlobalConstant::STATUS_CLOSED,
                    'vehicle_status' => GlobalConstant::STATUS_AVAILABLE,
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Booking updated and vehicle status changed');
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            activity()
                ->withProperties([
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                    'booking_id' => $id,
                    'error' => $e->getMessage(),
                ])
                ->log('Error occurred while updating booking');

            throw $e;
            report($e);
            return $e;
        }
    }

    public function updateApproval($request, $id)
    {
        try {
            DB::beginTransaction();
            $bookingApproval = BookingApproval::where(function ($q) {
                if (authUser()->role == 'approver') {
                    $userId = authUser()->id;
                    $q->where('user_approver_id', $userId);
                }
            })->find($id);

            if (!$bookingApproval) throw new \Exception('Data tidak ditemukan');

            $bookingApproval->update([
                'note' => $request->note,
                'status' => $request->status
            ]);
            $checkCountStatus = "SELECT
                        count(*) as total,
                        count(case when status = 'pending' then 1 else null end) as pending,
                        count(case when status = 'approved' then 1 else null end) as approve,
                        count(case when status = 'rejected' then 1 else null end) as rejected
                        FROM booking_approvals WHERE booking_id = $bookingApproval->booking_id";
            $result = DB::select(DB::raw("SELECT * FROM ($checkCountStatus) as t"));

            $bookingApprovalLast = BookingApproval::where('booking_id', $bookingApproval->booking_id)->latest()->first();
            $booking = Booking::find($bookingApproval->booking_id);
            if ($result[0]->approve == $result[0]->total) {
                $booking->update([
                    'status' => GlobalConstant::STATUS_APPROVED
                ]);
            } else if (($result[0]->approve < 2 && $result[0]->rejected >= 1) || ($bookingApprovalLast->status == GlobalConstant::STATUS_REJECTED)) {
                $booking->update([
                    'status' => GlobalConstant::STATUS_REJECTED
                ]);
                $vehicle = Vehicle::find($booking->vehicle_id);
                if ($vehicle) {
                    $vehicle->update([
                        'status' => GlobalConstant::STATUS_AVAILABLE
                    ]);
                }
            }

            activity()
                ->performedOn($bookingApproval)
                ->withProperties([
                    'data' => $bookingApproval,
                    'note' => $request->note,
                    'status' => $request->status,
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Booking approval updated successfully');
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            activity()
                ->withProperties([
                    'note' => $request->note ?? '',
                    'status' => $request->status ?? '',
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                    'error' => $e->getMessage()
                ])
                ->log('Error booking approval updated');
            throw $e;
            report($e);
            return $e;
        }
    }
}

<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class LaporanService
{
    public function getReport($request)
    {
        try {
            $query = Booking::with(['approvals.approver', 'vehicle', 'driver']);
            if (isset($request->from) && isset($request->to)) {
                $date_from = Carbon::parse($request->from)->startOfDay();
                $date_to = Carbon::parse($request->to)->endOfDay();
                $query->whereDate('date', '>=', $date_from)
                    ->whereDate('date', '<=', $date_to);
            }
            $data = $query->latest()->get();

            activity()
                ->withProperties([
                    'data' => $data,
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Data report successfully');
            return $data;
        } catch (\Exception $e) {
            activity()
                ->withProperties([
                    'user_id' => auth()->id(),
                    'user_role' => authUser()->role,
                ])
                ->log('Error Data report');
            throw $e;
            report($e);
            return $e;
        }
    }
}

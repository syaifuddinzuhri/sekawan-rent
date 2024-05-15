<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getReport($request)
    {
        try {
            $query = Booking::with(['vehicle'])
                ->select('vehicle_id', DB::raw('COUNT(*) as booking_count'))
                ->groupBy('vehicle_id')
                ->orderByDesc('booking_count')
                ->limit(10);

            $topVehicles = $query->get();
            $data = [];
            $categories = [];
            foreach ($topVehicles as $key => $item) {
                $data[] = $item->booking_count;
                $categories[] = $item->vehicle ? $item->vehicle->police_number . ' - ' . $item->vehicle->name . ' | ' . $item->vehicle->brand : '';
            }
            return [
                'data' => $data,
                'categories' => $categories
            ];
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }
}

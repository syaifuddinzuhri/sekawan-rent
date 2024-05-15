<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->data as $item) {
            $mainRow = [
                $item->date,
                $item->vehicle ? $item->vehicle->police_number . ' - ' . $item->vehicle->name . ' | ' . $item->vehicle->brand : '',
                $item->driver->name ?? '',
                $item->approvals[0]->approver->name ?? '',
                statusValue($item->approvals[0]->status ?? ''),
                $item->approvals[0]->note ?? '',
                statusValue($item->status),
            ];

            $rows[] = $mainRow;

            if (count($item->approvals) > 1) {
                foreach ($item->approvals as $key => $approval) {
                    if ($key > 0) {
                        $subRow = [
                            '',
                            '',
                            '',
                            $approval->approver->name ?? '',
                            statusValue($approval->status ?? ''),
                            $approval->note ?? '',
                            '',
                        ];
                        $rows[] = $subRow;
                    }
                }
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kendaraan',
            'Driver',
            'Approver Nama',
            'Approver Status',
            'Approver Keterangan',
            'Status',
        ];
    }
}

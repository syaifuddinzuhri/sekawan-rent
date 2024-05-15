<?php

use App\Constants\GlobalConstant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

if (!function_exists('formatIDR')) {
    function formatIDR($value, $is_prefix = false)
    {
        $format = number_format($value, 0, '.', '.');
        return $is_prefix ? 'Rp ' . $format : $format;
    }
}

if (!function_exists('dbIDR')) {
    function dbIDR($value)
    {
        return str_replace('.', '', $value);
    }
}

if (!function_exists('authUser')) {
    function authUser()
    {
        return User::find(auth()->user()->id);
    }
}

if (!function_exists('showSuccessToast')) {
    function showSuccessToast($message)
    {
        toast($message, 'success');
    }
}

if (!function_exists('showErrorToast')) {
    function showErrorToast($message)
    {
        toast($message, 'error');
    }
}

if (!function_exists('showSuccessAlert')) {
    function showSuccessAlert($message)
    {
        Alert::success('Success', $message);
    }
}

if (!function_exists('showErrorAlert')) {
    function showErrorAlert($message)
    {
        Alert::error('Error', $message);
    }
}


if (!function_exists('encryptData')) {
    function encryptData($value)
    {
        return Crypt::encrypt($value);
    }
}

if (!function_exists('decryptData')) {
    function decryptData($value)
    {
        return Crypt::decrypt($value);
    }
}

if (!function_exists('apiException')) {
    function apiException($msg)
    {
        throw new Exception($msg, 400);
    }
}

if (!function_exists('roleOptions')) {
    function roleOptions()
    {
        return [
            [
                'value' => GlobalConstant::ROLE_ADMIN,
                'label' => 'Admin'
            ],
            [
                'value' => GlobalConstant::ROLE_APPROVER,
                'label' => 'Approver'
            ],
            [
                'value' => GlobalConstant::ROLE_DRIVER,
                'label' => 'Driver'
            ],
        ];
    }
}

if (!function_exists('statusOptions')) {
    function statusOptions()
    {
        return [
            [
                'value' => GlobalConstant::STATUS_PENDING,
                'label' => 'Menunggu Persetujuan'
            ],
            [
                'value' => GlobalConstant::STATUS_APPROVED,
                'label' => 'Disetujui'
            ],
            [
                'value' => GlobalConstant::STATUS_REJECTED,
                'label' => 'Ditolak'
            ],
        ];
    }
}

if (!function_exists('statusBookingOptions')) {
    function statusBookingOptions()
    {
        return [
            [
                'value' => GlobalConstant::STATUS_PENDING,
                'label' => 'Menunggu Persetujuan'
            ],
            [
                'value' => GlobalConstant::STATUS_CLOSED,
                'label' => 'Ditutup'
            ],
        ];
    }
}


if (!function_exists('roleBadge')) {
    function roleBadge($value)
    {
        if ($value == GlobalConstant::ROLE_ADMIN) {
            return '<span class="badge badge-pill badge-primary">Admin</span>';
        } else if ($value === GlobalConstant::ROLE_APPROVER) {
            return '<span class="badge badge-pill badge-success">Approver</span>';
        } else {
            return '<span class="badge badge-pill badge-warning">Driver</span>';
        }
    }
}

if (!function_exists('statusVehicleBadge')) {
    function statusVehicleBadge($value)
    {
        if ($value == GlobalConstant::STATUS_AVAILABLE) {
            return '<span class="badge badge-pill badge-success">Tersedia</span>';
        } else {
            return '<span class="badge badge-pill badge-danger">Tidak Tersedia</span>';
        }
    }
}

if (!function_exists('statusBadge')) {
    function statusBadge($value)
    {
        if ($value == GlobalConstant::STATUS_PENDING) {
            return '<span class="badge badge-pill badge-warning">Menunggu Persetujuan</span>';
        } else if ($value == GlobalConstant::STATUS_APPROVED) {
            return '<span class="badge badge-pill badge-success">Disetujui</span>';
        } else if ($value == GlobalConstant::STATUS_REJECTED) {
            return '<span class="badge badge-pill badge-danger">Ditolak</span>';
        } else {
            return '<span class="badge badge-pill badge-info">Selesai</span>';
        }
    }
}

if (!function_exists('statusValue')) {
    function statusValue($value)
    {
        if ($value == GlobalConstant::STATUS_PENDING) {
            return 'Menunggu Persetujuan';
        } else if ($value == GlobalConstant::STATUS_APPROVED) {
            return 'Disetujui';
        } else if ($value == GlobalConstant::STATUS_REJECTED) {
            return 'Ditolak';
        } else if ($value == GlobalConstant::STATUS_CLOSED) {
            return 'Selesai';
        } else {
            return '';
        }
    }
}

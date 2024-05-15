<?php

namespace App\Constants;

class GlobalConstant
{
    const ROLE_ADMIN = 'admin';
    const ROLE_APPROVER = 'approver';
    const ROLE_DRIVER = 'driver';
    const ROLES = [self::ROLE_ADMIN, self::ROLE_APPROVER, self::ROLE_DRIVER];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CLOSED = 'closed';
    const STATUS_AVAILABLE = 'available';
    const STATUS_NOT_AVAILABLE = 'not-available';
    const STATUS_VEHICLE = [self::STATUS_AVAILABLE, self::STATUS_NOT_AVAILABLE];
    const STATUS = [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_CLOSED];
}

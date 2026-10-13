<?php

namespace App\Enums;

/**
 * A consultant's own, lightweight status for their customer — deliberately
 * separate from LeadStatus, which describes ITM's own service pipeline and
 * has no bearing on a consultant's independent book of business.
 */
enum ConsultantCustomerStage: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Done = 'done';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::InProgress => 'In progress',
            self::Done => 'Done',
        };
    }

    /** Maps to the design system's badge variants (§04). */
    public function badge(): string
    {
        return match ($this) {
            self::New => 'info',
            self::InProgress => 'attention',
            self::Done => 'success',
        };
    }
}

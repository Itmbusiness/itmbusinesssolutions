<?php

namespace App\Enums;

/**
 * The PRD's lead status model:
 * New → Contact Attempted → Qualified → Awaiting Information →
 * Quote in Preparation → Converted / Unqualified / Closed
 */
enum LeadStatus: string
{
    case New = 'new';
    case ContactAttempted = 'contact-attempted';
    case Qualified = 'qualified';
    case AwaitingInformation = 'awaiting-information';
    case QuoteInPreparation = 'quote-in-preparation';
    case Converted = 'converted';
    case Unqualified = 'unqualified';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::ContactAttempted => 'Contact attempted',
            self::Qualified => 'Qualified',
            self::AwaitingInformation => 'Awaiting information',
            self::QuoteInPreparation => 'Quote in preparation',
            self::Converted => 'Converted',
            self::Unqualified => 'Unqualified',
            self::Closed => 'Closed',
        };
    }

    /** Maps to the design system's badge variants (§04). */
    public function badge(): string
    {
        return match ($this) {
            self::New, self::QuoteInPreparation => 'info',
            self::Converted => 'success',
            self::AwaitingInformation, self::ContactAttempted => 'attention',
            self::Unqualified => 'problem',
            self::Qualified => 'info',
            self::Closed => 'neutral',
        };
    }

    public function isOpen(): bool
    {
        return ! in_array($this, [self::Converted, self::Unqualified, self::Closed], true);
    }
}

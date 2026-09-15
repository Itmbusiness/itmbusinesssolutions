<?php

namespace App\Enums;

/**
 * Mirrors the five outcomes on the public homepage, plus the board's
 * "I am not sure" option, so the request form and the marketing copy
 * never drift apart.
 */
enum ServiceGoal: string
{
    case StartABusiness = 'start-a-business';
    case TaxCompliance = 'tax-compliance';
    case Tenders = 'tenders';
    case EmployerCompliance = 'employer-compliance';
    case OrganisationsTrusts = 'organisations-trusts';
    case NotSure = 'not-sure';

    public function label(): string
    {
        return match ($this) {
            self::StartABusiness => 'Start a Business',
            self::TaxCompliance => 'Become Tax Compliant',
            self::Tenders => 'Prepare for Tenders',
            self::EmployerCompliance => 'Employer Compliance',
            self::OrganisationsTrusts => 'Organisations & Trusts',
            self::NotSure => 'I am not sure',
        };
    }
}

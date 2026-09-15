# Acceptance and Test Catalogue

## Public and SEO

- All inventory routes resolve correctly.
- Titles, descriptions and H1 values are unique.
- Canonicals and redirects are correct.
- Structured data matches visible content.
- Forms work at narrow mobile widths and under throttled connectivity.

## Authorisation

- Every role is tested against every protected action.
- A member of Organisation A cannot discover Organisation B through URLs, search, exports, notifications or filenames.
- Removed and expired invitations cannot be used.
- At least one owner remains per organisation.

## Quotation and payment

- Earlier quote versions cannot be approved after revision.
- Expired quotes cannot request payment.
- Professional and statutory line items remain distinguishable.
- Duplicate payment callbacks do not create duplicate receipts.

## Documents

- Unsupported and malicious files are rejected safely.
- Rejected documents show an actionable reason.
- Downloads require permission and are audited.
- Expired documents trigger the correct workflow.

## Content governance

- Regulated pages cannot publish without reviewer and next-review date.
- Stale-content alerts are generated.
- Unpublishing creates the intended redirect or user explanation.

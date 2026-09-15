# Quotation Detail

**Audience:** Authorised back-office roles  
**Indexing:** Noindex, authenticated  

## Purpose

Create versioned line items, separate professional and statutory charges, add assumptions, request review, send and view decision history.

## Page structure

1. Context header with title, filters and permitted primary action.
2. Summary or risk indicators relevant to this page.
3. Searchable main table, timeline, board or detail workspace.
4. Secondary information drawer rather than disruptive page changes where appropriate.
5. Activity and audit context for material records.

## Page-specific information

- Client and organisation
- Version history
- Line items by fee type
- Assumptions
- Exclusions
- Terms
- Decision audit

## Permitted actions to design

- Add line item
- Create revision
- Submit for review
- Send current version
- Withdraw

## Required behaviours

- Preserve filter state and support shareable internal views without leaking data.
- Enforce permissions on the server, not only by hiding buttons.
- Show organisation and record context before any destructive or binding action.
- Require a reason for high-impact status, access, payment or publication changes.
- Separate client-visible notes from internal notes.

## States

Loading · Empty · Filtered empty · Partial data · Permission denied · Recoverable error · Offline or queued · Success

## Mobile behaviour

Prioritise urgent summaries and actions. Replace wide tables with cards or drill-down rows; never require horizontal scrolling for core actions.

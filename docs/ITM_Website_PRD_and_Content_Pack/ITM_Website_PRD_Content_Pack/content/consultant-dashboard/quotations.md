# Quotations

**Audience:** Authorised back-office roles  
**Indexing:** Noindex, authenticated  

## Purpose

Manage drafts, internal review, sent, viewed, change-requested, approved, rejected and expired quotations.

## Page structure

1. Context header with title, filters and permitted primary action.
2. Summary or risk indicators relevant to this page.
3. Searchable main table, timeline, board or detail workspace.
4. Secondary information drawer rather than disruptive page changes where appropriate.
5. Activity and audit context for material records.

## Page-specific information

- Quote number
- Client
- Organisation
- Services
- Version
- Value
- Validity
- Decision state

## Permitted actions to design

- Create quote
- Request internal review
- Send
- Duplicate as new draft
- Cancel

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

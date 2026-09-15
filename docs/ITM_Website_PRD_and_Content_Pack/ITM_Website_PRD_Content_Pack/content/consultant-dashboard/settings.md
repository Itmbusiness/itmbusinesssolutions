# Operational Settings

**Audience:** Authorised back-office roles  
**Indexing:** Noindex, authenticated  

## Purpose

Configure business identity, contacts, notification templates, payment methods, working hours, currencies, document rules and workflow defaults.

## Page structure

1. Context header with title, filters and permitted primary action.
2. Summary or risk indicators relevant to this page.
3. Searchable main table, timeline, board or detail workspace.
4. Secondary information drawer rather than disruptive page changes where appropriate.
5. Activity and audit context for material records.

## Page-specific information

- Business identity
- Contact channels
- Payment methods
- Notification templates
- Document policy
- Workflow defaults
- Security controls

## Permitted actions to design

- Edit section
- Test template
- Request privileged approval
- View change history

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

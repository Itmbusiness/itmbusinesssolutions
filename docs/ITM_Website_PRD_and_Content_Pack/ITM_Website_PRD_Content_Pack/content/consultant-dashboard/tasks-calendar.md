# Tasks and Calendar

**Audience:** Authorised back-office roles  
**Indexing:** Noindex, authenticated  

## Purpose

Combine assigned operational tasks, application due dates, compliance dates, content reviews and follow-up reminders with filters and ownership.

## Page structure

1. Context header with title, filters and permitted primary action.
2. Summary or risk indicators relevant to this page.
3. Searchable main table, timeline, board or detail workspace.
4. Secondary information drawer rather than disruptive page changes where appropriate.
5. Activity and audit context for material records.

## Page-specific information

- My tasks
- Team tasks
- Compliance deadlines
- Content reviews
- Follow-ups
- Overdue items

## Permitted actions to design

- Create task
- Assign
- Reschedule
- Complete
- Escalate

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

# Client vs. consultant dashboards — role definition

This is a specification, not an implementation. It exists to settle what each
role's dashboard is *for* before any schema or Livewire work starts, because
the two roles turned out to be more different than the current scaffold
assumes.

## The two roles, precisely

**ITM Business Solutions** is itself a consultancy/agency: company
registration, tax, and compliance work done by ITM's own staff for ITM's own
clients. That relationship — Journey A/B in the PRD — is what the public site,
the lead pipeline, and the admin CMS already serve.

**Consultants** are a second, unrelated audience: independent professionals
who register on the ITM site to use it as their own lightweight CRM. A
consultant's customers are *their* customers — sourced, owned, and managed by
them. ITM does not introduce them, does not see their pipeline, and does not
mediate the relationship. The platform's role is purely to be the tool a
consultant uses to run their own book of business.

The load-bearing rule: **a consultant never touches an ITM lead, and an ITM
client never touches a consultant's customer.** They are two tenants of the
same site, not two tiers of one workflow. Nothing in the client dashboard
should reference consultants, and nothing in the consultant dashboard should
reference ITM's own leads, quotations, or clients.

This is a deliberate correction to the current build: `pages/dashboard/
consultant.blade.php` and `Lead::assigned_to` were built on the opposite
assumption — that a "consultant" is an ITM staff member working ITM's own
leads. That model is now wrong and should not be extended; see "What changes"
below.

## Client dashboard — what it offers

Audience: a person or organisation getting compliance/registration work done
*by ITM*. Everything here is scoped to that one relationship.

- **Overview** — status of their open requests at a glance: awaiting
  quotation, quotation ready, in progress, action required (e.g. a document
  ITM is waiting on).
- **Requests / applications** — every service they've asked ITM for, each
  with its `Lead`/application reference, current `LeadStatus`, and a plain-
  language description of what happens next. This is the existing `leads`
  table, scoped to `user_id`.
- **Quotations** — once quotations are modelled (PRD Release 2), the
  quotation(s) ITM has issued them, for review/acceptance.
- **Documents** — once the document vault exists (Release 3), the files ITM
  has requested and the files the client has supplied, with status per file.
- **New request** — the existing `service-requests.create` flow (Journey B),
  pre-filled from their account.
- **Account/profile** — organisation details, contact preferences (email/
  WhatsApp/phone), consent history. No visibility into ITM's operations, no
  visibility into any consultant's data.

Nothing here should ever surface a consultant, another client, or anything
outside this one client's own relationship with ITM.

## Consultant dashboard — what it offers

Audience: an independent consultant/agent who is *not* working for or with
ITM's clients. The goal is to make their own client-facing work easier — this
is a tool for them, not a channel into ITM.

- **Overview** — counts scoped to their own book: their customers, their open
  items, anything needing follow-up. Zero ITM data anywhere on this screen.
- **My customers** — a consultant-owned customer list (a new table, e.g.
  `consultant_customers` or `customers` scoped by `consultant_id` — *not* the
  `leads` table, which stays ITM-client-only). Name, contact details, notes,
  and whatever light status the consultant wants to track for their own
  process (new / in progress / done, or similar — consultant-defined, not the
  PRD's `LeadStatus`, since that status model describes ITM's own service
  pipeline).
- **Notes / activity log per customer** — freeform notes and a timestamped
  history, so a consultant has a record of what's been done for each
  customer without needing their own tool.
- **Simple task/reminder list** — "follow up with X on Friday" style
  reminders tied to a customer, since this is meant to make their life
  easier, not to be a full project-management suite.
- **Account/profile** — their own contact details, nothing ITM-specific.

Explicitly **not** in scope for the consultant dashboard: ITM's leads, ITM's
clients, ITM's quotations, assignment queues, or anything implying the
consultant works cases on ITM's behalf. If a future feature genuinely needs
consultants to interact with ITM's own clients (e.g. ITM subcontracting work
to a vetted consultant), that is a different, explicitly-scoped feature and
should not be folded into this one by default.

## Implementation status

**Implemented.** Both dashboards now match the model above:

- `app/Http/Controllers/DashboardController.php` prepares role-scoped data
  (a client's own leads; a consultant's own customers and open tasks) —
  `/dashboard` was previously a static `Route::view()` with no data.
- `consultant_customers`, `consultant_customer_notes`, and `consultant_tasks`
  are new tables (`ConsultantCustomer`, `ConsultantCustomerNote`,
  `ConsultantTask` models), all scoped by `consultant_id` and never joined to
  `leads`. `ConsultantCustomerStage` is the consultant's own lightweight
  status enum, separate from `LeadStatus`.
- `routes/consultant.php` (`Consultant\CustomerController`,
  `CustomerNoteController`, `TaskController`) is guarded by a new
  `consultant` middleware alias (`EnsureUserIsConsultant`), mirroring how
  `admin` guards `/admin`. Every controller action re-checks
  `$customer->consultant_id === Auth::id()` so one consultant can never reach
  another's customers.
- `resources/views/pages/dashboard/consultant.blade.php` no longer mentions
  leads, quotations, or client actions — it shows the consultant's own
  customer count, open task count, a recent-customers list, and an
  open-tasks list with an inline complete/reopen toggle.
- `Lead::assigned_to` was left untouched — it was never repurposed for
  consultant data, and remains open for ITM's own internal case-assignment
  story (admin staff, not consultants) if that gets designed later.

Not implemented, per the open questions below: consultant registration
approval/gating, and monetization.

## Open questions (deliberately not decided here)

- **Monetization** — subscription, commission, or free/approved-only access.
  Left open per your instruction; it affects whether registration is
  self-serve or admin-gated, but not the dashboard *content* defined above.
- **Data model for consultant customers** — a new migration/model
  (`consultant_customers` or similar) is needed; not created in this pass
  since this document is definition-only.
- **Registration flow** — whether a self-selected "Consultant" role (already
  allowed by `UserRole::selfSelectable()`) is enough, or whether consultant
  accounts need admin approval before they can use the dashboard, given they
  will be storing their own customers' personal data on ITM's infrastructure
  (a data-processing consideration worth resolving before this ships).

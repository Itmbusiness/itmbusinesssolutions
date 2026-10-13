# Unified growth system: implementation plan

Based on the supplied blueprint's matching DOCX text (`ITM_Unified_Growth_System_Implementation_Blueprint.pdf`, version 1.0, 11 October 2026). The existing Laravel application remains the system of record. Instagram and an assumed Facebook Shop are outside this plan.

## Current baseline

The repository uses Laravel 13, Livewire 4, Blade, and a database-driven service catalog. It already has guest and signed-in enquiry forms, a `leads` table, admin lead status and assignment, queued confirmation/notification mail, and a consultant customer workspace. Local development uses SQLite; production database and queue configuration must be confirmed from the deployed environment. The consultant's private customer book is separate from ITM's leads. There are no verified quotations, applications, payments, Meta accounts, WhatsApp Cloud API, or marketing permission records in this codebase.

## Sequence and acceptance gates

| Phase | Site and system changes | Gate |
| --- | --- | --- |
| 0. Discovery | Inventory deployed routes, database, email/queue workers, current lead response process, consent text, Meta asset ownership, WhatsApp number, and baseline enquiry counts. Confirm who may see customer data and who owns each lead. | Written access and integration inventory; approved lead stages, privacy/retention rules, and baseline KPIs. |
| 1. Reliable website capture | Keep the existing lead flow. Record bounded campaign UTMs from landing to enquiry; show them in admin. Restrict assignment to staff. Next, add a separate, unchecked marketing opt-in with timestamp/policy version and an opt-out path; introduce contact/opportunity records only after agreeing identity and stage rules. | Guest and signed-in enquiries create once, show their source, remain unassigned or assigned to staff, and never imply marketing consent. |
| 2. CRM workflow | Add opportunity ownership, stage transition history, response tasks and SLA timestamps around ITM leads. Define quotations and applications as separate reviewed workstreams; preserve existing records. Add staff reports for source, response time and pipeline age. | A controlled test lead can be owned, contacted, qualified, quoted and closed with an audit trail. |
| 3. Channel integrations | After business asset access is verified, add signed, idempotent Meta webhook intake with queued processing; pilot Facebook Lead Ads and official WhatsApp Cloud API inbox/handoff. Confirm number coexistence or migration before connection. | Webhook retries create one lead/message; staff can handle a test conversation and failed jobs are visible. |
| 4. Catalog and measurement | Extend the Laravel catalog with approved pricing and eligibility fields. Validate each Meta surface and service before sync. Add consent-gated Pixel/CAPI events with shared event IDs, minimal payloads and outcome reporting. | Ineligible services never sync; failures retry safely; browser/server events deduplicate. |
| 5. Lifecycle and launch | Add consented email journeys, reviewed compliance reminders, unsubscribe/preference handling, monitoring, staff training and operational dashboards. | Opt-out blocks promotion, operational notices remain appropriate, and end-to-end UAT passes. |

## Decisions needed before external integration

- Business owner: Meta Business Portfolio, Page/app ownership, ad account and domain verification.
- Operations: WhatsApp Business number ownership, Cloud API/coexistence path, staffing and response hours.
- Sales and finance: lead stages, quotation approval, pricing visibility and the definition of a won sale.
- Privacy reviewer: marketing consent wording, policy version, retention period, opt-out process and local legal review.
- Technical owner: production database, queues/workers, mail provider, secret storage, monitoring and deployment access.

Do not scale paid campaigns until enquiry capture, staff response, attribution, consent and lead-to-sale tracking pass their gates. Set numerical growth targets after collecting a reliable baseline; the blueprint's week estimates depend on access and approvals.

## Work started in this change

Phase 1 begins with session-based `utm_source`, `utm_medium` and `utm_campaign` capture for website and workspace requests. The values are limited in length, stored separately from the existing `source` field, and shown only in the admin lead detail. Lead assignment validation now matches the staff-only choices in the admin form. External account integration and promotional messaging wait for the decisions above.

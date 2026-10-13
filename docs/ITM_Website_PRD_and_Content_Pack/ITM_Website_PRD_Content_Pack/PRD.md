# Product Requirements Document

## Product

**Working name:** ITM Business Solutions Digital Service Platform  
**Document status:** Implementation-ready baseline  
**Technology direction:** Technology-neutral  
**Primary market:** Zimbabwe  
**Primary channel relationship:** Website discovery and self-service supported by WhatsApp  

## 1. Executive summary

The product is a mobile-first website and secure service-delivery platform for company formation, tax and fiscal compliance, employer compliance, supplier onboarding, business documents, trusts and operating licences.

It must serve two equally important purposes:

1. Convert visitors into qualified quotation requests.
2. Give clients and consultants a shared, auditable workspace for organisations, documents, quotations, payments, applications, deadlines and communication.

The platform must not behave like a simple brochure or force visitors to understand regulatory terminology. It should begin with the outcome the visitor wants, recommend relevant services, explain requirements before payment and preserve human consultant review for high-risk or changing matters.

## 2. Product vision

> Help Zimbabwean entrepreneurs start correctly, remain compliant and prepare their organisations for growth and opportunities through clear information, human guidance and visible progress.

## 3. Business objectives

- Increase qualified leads from organic search, referrals and social campaigns.
- Convert service enquiries into approved written quotations.
- Reduce repetitive requirement and status questions on WhatsApp.
- Increase cross-selling between registration, tax, NSSA, tender and branding services.
- Create recurring revenue from returns, renewals and ongoing compliance plans.
- Maintain a structured client and organisation database.
- Reduce missing-document delays and internal handover errors.
- Publish useful, reviewable articles that build authority without presenting outdated information as current advice.

## 4. User outcomes

### Public visitor

- Understand which service or package fits the desired outcome.
- See requirements, limitations and indicative process before requesting a quotation.
- Submit a concise request without creating an account.
- Continue through WhatsApp using a service-specific prefilled message.

### Client organisation owner

- Manage several organisations from one account.
- Invite team members with controlled permissions.
- Review and approve quotations.
- Make or record payment after approval.
- Upload documents securely and track every application.
- Receive compliance and renewal reminders.

### Consultant

- Qualify leads and create client and organisation records without duplication.
- Request specific documents using reusable checklists.
- Prepare versioned quotations and record approval.
- Move applications through controlled statuses.
- Assign tasks, request review and maintain an audit trail.
- Identify cross-sell and renewal opportunities.

### Management

- See leads, conversion, revenue, workload, delays, upcoming deadlines and content-review risks.
- Configure services, prices, requirements and content without a software release.

## 5. Product principles

1. **Outcome before terminology:** Start with what the visitor wants to accomplish.
2. **No payment before clarity:** A written quotation must be approved before payment is requested.
3. **Separate charges:** Professional, statutory and optional charges are separate line items.
4. **Mobile and low-bandwidth first:** Core actions must work well on affordable smartphones and unstable connections.
5. **Human review for regulated work:** Recommendations are preliminary until assessed by a consultant.
6. **One account, many organisations:** Users should not create duplicate accounts for different entities.
7. **Client ownership of official access:** Official portal emails and credentials remain controlled by the client; sensitive access uses approved secure collection.
8. **No false guarantees:** Authority approval and processing times are never guaranteed.
9. **Current, dated content:** Regulated pages display review metadata and can be suspended when stale.
10. **Auditable work:** Material actions, approvals, uploads and status changes are recorded.

## 6. Scope

### Public website

- Homepage and outcome-based service discovery.
- Seven category pages and twenty service pages.
- Packages, requirement centre, resources and articles.
- Search, contact, about, quotation request, application tracking and policy pages.
- Service-specific WhatsApp links.
- Structured data, internal linking and SEO controls.

### Sales and quotation workflow

- Guest quotation request.
- Lead qualification and organisation matching.
- Requirements or clarification requests.
- Versioned quotation creation.
- Client approval, rejection, expiry or change request.
- Account creation or invitation following approval.
- Payment request and confirmation.

### Consultant workspace

- Leads, clients, organisations, applications, quotations, payments, tasks, documents, messages, compliance calendar, reports, content and configuration.

### Client workspace

- Multi-organisation overview, invitations, applications, quotations, payments, documents, compliance dates, reminders and messages.

### Content publishing

- Service, category, article, FAQ and static-page management.
- Author, reviewer, reviewed-on and next-review fields.
- Draft, review, scheduled, published, stale and archived states.

## 7. Out of scope for the first release

- Fully automated legal, tax or entity-structure decisions.
- Automatic submission to every government portal.
- Guaranteed real-time authority status where no official integration exists.
- Marketplace onboarding of unrelated third-party consultants.
- Full accounting or payroll functionality.
- Storage of official-portal passwords in ordinary notes or message fields.
- Public display of confidential client certificates or identity documents.

## 8. Personas

### New founder

Uses a phone, may not know the difference between company structures and wants a clear total estimate.

### Existing business owner

Has incomplete records, outstanding obligations or an approaching tender and needs a prioritised action plan.

### Organisation administrator

Uploads documents, reviews quotations and monitors several applications on behalf of the owner.

### External accountant or staff member

Needs access to selected tax or payroll tasks but should not see all owner or payment information.

### Consultant

Manages assigned leads and applications, requests information and communicates progress.

### Reviewer

Checks sensitive submissions or content before release or publication.

### Finance officer

Issues payment requests, reconciles payments and produces receipts without accessing unnecessary identity documents.

## 9. Information architecture

The public navigation is intentionally shallow: Home, Services, Packages, Requirements, Resources, About and Start Your Application. Service pages sit beneath seven category landing pages. Authentication and tracking remain visible utilities rather than primary marketing links.

Authenticated users enter an organisation-aware workspace. The current organisation is always visible and switching organisations must never mix data.

See `specifications/02-information-architecture.md`.

## 10. Core user journeys

### Journey A: Guest quotation request

1. Visitor lands on an article, category or service page.
2. Visitor reviews outcome, requirements, process and FAQs.
3. Visitor selects **Request a Quotation**.
4. The form captures contact details, organisation status, desired service, urgency and consent.
5. The system creates or matches a lead without creating a duplicate.
6. A consultant reviews the request and asks for only the documents needed to scope the work.
7. A versioned quotation is sent.
8. The client approves, rejects or requests a change.
9. Following approval, the client receives an account invitation and payment instructions.
10. Payment confirmation activates the application workflow.

### Journey B: Existing client requests another service

1. Client switches to the correct organisation.
2. Client selects a service or responds to a compliance recommendation.
3. Known organisation information pre-fills the request.
4. Only missing or expired requirements are requested.
5. Quotation, approval, payment and application follow the standard workflow.

### Journey C: Recurring compliance task

1. A rule or saved deadline creates an upcoming compliance item.
2. Client and assigned consultant receive staged reminders.
3. Client uploads period-specific records.
4. Consultant prepares work and requests client approval where required.
5. Submission evidence is stored.
6. The next recurrence is scheduled.

## 11. Functional requirements

### FR-01 Service discovery

- Visitors can browse by goal, category, service, business stage and search.
- Every service has a dedicated indexable page and stable canonical URL.
- Related services and packages are contextual, not generic.

### FR-02 Recommendation questionnaire

- The questionnaire asks only questions that change the recommendation.
- Results are labelled preliminary and can be sent to WhatsApp or quotation request.
- The answer set is attached to the lead for consultant review.

### FR-03 Quotation requests

- Guests can submit without an account.
- Required consent and preferred contact channel are recorded.
- Spam and duplicate controls are applied.
- The user receives a reference and confirmation.

### FR-04 Quotations

- A quotation has versions, validity date, currency, line items, taxes or adjustments where applicable, terms and attachments.
- Line items classify professional fees, statutory fees and optional expenses.
- A new version invalidates acceptance of earlier versions while preserving history.
- Approval records user, timestamp, version and accepted terms.
- Payment cannot be requested against an unapproved or expired quotation.

### FR-05 Organisations and teams

- One user can belong to many organisations.
- An organisation has type, identifiers, contacts, addresses and compliance dates.
- Organisation roles are Owner, Administrator, Contributor and Viewer.
- Invitations expire and can be revoked.
- At least one active owner must remain.

### FR-06 Applications

- An approved, payable scope creates one or more applications.
- Applications store organisation, service, assignee, reviewer, status, dates, documents, tasks and messages.
- Status changes follow configured transitions and create audit events.
- Client-visible notes are separated from internal notes.

### FR-07 Requirements and documents

- Each service owns a versioned requirement checklist.
- Requirements can be conditional by organisation type or circumstance.
- Uploads are classified, scanned, versioned and access-controlled.
- Expiry dates can be extracted or entered and confirmed manually.
- Rejection reasons are specific and visible to the uploader.

### FR-08 Payments

- Payment follows quotation approval.
- Supported methods are configurable.
- Manual proof upload and later payment-gateway integration are supported.
- Finance can mark pending, under review, confirmed, partially paid, refunded or failed.
- A receipt is generated only after confirmation.

### FR-09 Tracking

- Authenticated clients see full permitted application detail.
- Guest tracking uses a reference plus a second verification factor and exposes limited information.
- Internal notes and sensitive authority details never appear in guest tracking.

### FR-10 Communication

- Messages can be associated with a lead, quotation or application.
- WhatsApp deep links use a structured, service-specific message.
- Important decisions are captured in the platform even when discussion occurs externally.
- Notification preferences and delivery status are stored.

### FR-11 Compliance calendar and reminders

- Dates may belong to an organisation, registration, document, application or recurring obligation.
- Reminders support multiple stages and recipients.
- Users can acknowledge, assign or snooze where permitted.
- A reminder is not proof that an obligation has been completed.

### FR-12 Content management

- Editors control metadata, headings, body content, FAQs, links and CTA.
- Regulated content requires a reviewer and next-review date.
- Stale regulated content triggers alerts and can be automatically unpublished or labelled.
- Redirects are mandatory when published URLs change.

### FR-13 Search and SEO

- Public search covers services, categories, articles and requirements.
- Pages support canonical URL, index control, social image and structured data.
- XML sitemaps are separated by content type.
- Broken internal links and orphan pages are reportable.

### FR-14 Reporting

- Funnel: visit to request, qualified lead, quote sent, quote approved, paid, completed.
- Operations: time in status, overdue tasks, missing documents, consultant workload.
- Commercial: quoted and confirmed value by service and source.
- Content: organic landing pages, service-assisted conversions and stale pages.

## 12. Status models

### Lead

New → Contact Attempted → Qualified → Awaiting Information → Quote in Preparation → Converted / Unqualified / Closed

### Quotation

Draft → Internal Review → Sent → Viewed → Change Requested → Revised → Approved / Rejected / Expired / Cancelled

### Payment

Not Requested → Requested → Pending → Under Review → Confirmed / Partially Paid / Failed / Refunded

### Application

Draft → Awaiting Documents → Documents Under Review → Ready to Start → In Progress → Submitted to Authority → Authority Feedback → Client Action Required → Completed / On Hold / Cancelled

Transitions must be configurable. “Submitted to Authority” is used only when submission actually occurred and evidence or a responsible consultant confirmation is recorded.

## 13. Roles and permissions

Back-office roles: Super Administrator, Operations Administrator, Consultant, Reviewer, Finance Officer, Content Editor and Support Agent.

Client organisation roles: Owner, Administrator, Contributor and Viewer.

Permissions are action-based and organisation-scoped. See `specifications/03-roles-permissions.md`.

## 14. Data and privacy

The product processes identity records, registration records, tax information, payroll-related information, banking evidence and confidential organisational documents. Required controls include least-privilege access, encrypted transport and storage, malware scanning, audit logs, session controls, secure recovery, retention rules and documented incident response.

Sensitive credentials must not be stored in ordinary forms, messages or notes. Where access to an official portal is unavoidable, use a specifically approved secure process, collect the minimum necessary information and revoke access after the task.

## 15. Non-functional requirements

- Mobile-first responsive interface from 320px upward.
- Core content usable on slow or intermittent connections.
- Public core pages target strong Core Web Vitals and avoid heavy autoplay media.
- WCAG 2.2 AA target for core journeys.
- Plain-language errors with recovery actions.
- No cross-organisation data leakage under any role combination.
- Daily backups with tested restoration policy.
- Audit trail for privileged actions.
- Monitoring for availability, errors, failed notifications and queued work.
- Export capability for client records and operational reporting.
- Time, date and currency presentation appropriate to Zimbabwe while storing canonical values.

## 16. SEO requirements

- One primary intent per indexable page.
- Unique title, meta description, H1, body and FAQ content.
- Category pages target broad intent; service pages target transactional intent; articles target informational questions.
- Service pages include visible author or reviewer information, reviewed date and current-status notice where relevant.
- Use Organisation, Service, BreadcrumbList, Article and WebSite structured data where the visible content supports it.
- FAQ structured data is used only for visible FAQs and should not be relied on for guaranteed search enhancements.
- Avoid near-duplicate location pages and unsupported “best” or “number one” claims.
- Articles link to a relevant service, requirement guide and category page.

## 17. Analytics and success measures

Primary events: service viewed, requirements opened, recommendation completed, WhatsApp clicked, quote started, quote submitted, quote sent, quote approved, payment confirmed and application completed.

Initial success measures:

- Qualified quotation-request rate by landing page.
- Quote approval rate.
- Time from request to first consultant response.
- Time from approval to payment confirmation.
- Percentage of applications delayed by missing documents.
- Repeat-service and recurring-compliance uptake.
- Organic service-page and article-assisted conversions.
- Client status enquiries that could have been answered by the dashboard.

Targets should be set after a four-to-eight-week measurement baseline rather than invented in the PRD.

## 18. Release plan

### Release 1 — Sales foundation

Public pages, category and service pages, articles, quotation request, lead management, quotations, WhatsApp deep links, basic organisations and analytics.

### Release 2 — Shared service delivery

Client accounts, multi-organisation teams, applications, requirements, document uploads, messages, payments and tracking.

### Release 3 — Compliance retention

Compliance calendar, recurring tasks, reminders, document expiry, service plans and management reporting.

### Release 4 — Optimisation and integrations

Approved payment integrations, messaging integrations, selective official-system integrations where lawful and available, advanced reporting and workflow automation.

## 19. Acceptance criteria for product launch

- Every route in `page-inventory.csv` exists or is explicitly deferred.
- All twenty service pages contain unique content and metadata.
- Quote approval is recorded before payment request.
- A client can manage two organisations without any data crossing between them.
- Invitation and permission tests cover every client role.
- Consultant roles cannot access functions outside their permissions.
- Uploaded documents are private, scanned and auditable.
- Regulated content has a reviewer and next-review date.
- Public pages pass mobile, accessibility, broken-link and structured-data checks.
- Analytics events are validated in a staging environment.
- Backup restoration and account-recovery procedures are tested.

## 20. Open business inputs

These are configuration inputs, not unresolved product architecture:

- Verified business registration and contact details.
- Professional fee schedule and quotation terms.
- Payment methods and refund policy.
- Operational service-level targets.
- Authorised consultant and reviewer list.
- Document-retention periods.
- Exact official authorities and processes for each regulated service.

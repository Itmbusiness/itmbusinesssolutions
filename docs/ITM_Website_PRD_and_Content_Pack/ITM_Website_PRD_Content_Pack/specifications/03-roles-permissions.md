# Roles and Permissions

## Back-office roles

### Super Administrator

Full platform administration, security settings, role management and audit access.

### Operations Administrator

Manages clients, organisations, applications, assignments, workflows and service configuration. Cannot alter security-owner controls.

### Consultant

Manages assigned leads, organisations, applications, requirements, messages and draft quotations.

### Reviewer

Approves designated submissions, sensitive documents, quotations or regulated content. Review must be independent where configured.

### Finance Officer

Views approved quotations, records payments, issues receipts and performs reconciliation. Identity-document access is denied unless explicitly required.

### Content Editor

Drafts public content and metadata. Cannot publish regulated content without reviewer approval.

### Support Agent

Handles general messages and status explanations with limited financial and document access.

## Client organisation roles

### Owner

Controls organisation membership, accepts binding quotations, views payments and can request data export or closure.

### Administrator

Manages organisation information, members, documents and applications. Quotation approval can be enabled or withheld by the owner.

### Contributor

Uploads documents, responds to requests and comments on assigned applications.

### Viewer

Read-only access to permitted organisation records.

## Permission principles

- Deny by default.
- Scope every client action to an organisation membership.
- Separate view, create, edit, approve, submit, download and delete permissions.
- Preserve at least one active organisation owner.
- Log role, membership and approval changes.
- Provide temporary, expiring access rather than shared passwords.

# Non-functional and Security Requirements

## Performance

- Prioritise text and primary CTA before decorative media.
- Optimise responsive images and defer non-critical assets.
- Preserve completed form fields during intermittent connectivity.
- Avoid large client-side bundles for public pages.

## Accessibility

- WCAG 2.2 AA target.
- Keyboard-complete workflows.
- Visible focus, clear labels and programmatic errors.
- No colour-only status meaning.
- Accessible document-upload progress and validation.

## Security

- Strong authentication and optional multi-factor authentication, mandatory for privileged staff.
- Secure password recovery and session revocation.
- Server-side authorisation on every protected action.
- Encryption in transit and at rest.
- Malware scanning and restricted file types.
- Rate limiting, bot protection and audit logging.
- Secrets excluded from messages, logs and analytics.
- Tested backups, restoration and incident response.

## Privacy

- Data-minimising forms.
- Purpose and consent recorded.
- Role-limited access to identity, tax, payroll and banking records.
- Retention and deletion rules by document type.
- Export and correction workflow.
- No public indexing of client or application data.

## Reliability

- Idempotent payment and notification handling.
- Background-work retries with visibility.
- Clear degraded-mode messaging.
- Monitoring for failed uploads, notifications, payment callbacks and scheduled reminders.

---
seo_title: "Reset Password | ITM"
indexable: false
---

# Reset Password

## Purpose

Validate the recovery token, require a strong new password, revoke risky sessions where appropriate and confirm successful reset without exposing account history.

## Required interface content

- Clear page title and reason for the step
- Labelled fields and accessible validation
- Primary action and safe secondary navigation
- Help route that does not bypass verification
- Session, privacy and security messaging appropriate to the action

## States

Default · Loading · Invalid input · Expired or invalid token · Rate limited · Success · Service temporarily unavailable

## Security note

Codes expire quickly and should never be forwarded. Repeated failures trigger a temporary security limit and a safe recovery route.

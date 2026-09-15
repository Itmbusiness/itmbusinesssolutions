---
seo_title: "Verify Access | ITM"
indexable: false
---

# Verify Access

## Purpose

Handle one-time codes or multi-factor challenges with expiry, retry controls, recovery guidance and safe rate-limit messaging.

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

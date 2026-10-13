---
seo_title: "Forgot Password | ITM Business Solutions"
indexable: false
---

# Forgot Password

## Purpose

Collect email or mobile identifier and always show a neutral response. Send a time-limited recovery step and record security events.

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

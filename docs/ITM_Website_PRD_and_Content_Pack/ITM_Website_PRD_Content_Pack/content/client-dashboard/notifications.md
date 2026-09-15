# Notifications

**Audience:** Authenticated client users with organisation-scoped permissions  
**Indexing:** Noindex, authenticated  

## Purpose

Show quotation decisions, payment updates, document requests, application changes, invitations and compliance reminders with channel preferences.

## Page content

- Persistent organisation switcher with organisation name and user role.
- Plain-language status and next action.
- Relevant records only for organisations the user can access.
- Help text that explains what ITM is waiting for and what happens next.
- Primary action positioned near the item that requires attention.

## Page-specific information

- Type
- Organisation
- Related record
- Message
- Date
- Read state

## Actions to design

- Open action
- Mark read
- Mark all read
- Change notification preference

## Permission behaviour

Owners, Administrators, Contributors and Viewers see only actions granted to their role. Binding quotation approval, member administration, payment information and document access are independently permissioned.

## States

Loading · Empty with useful next step · Action required · Under review · Completed · Permission limited · Error with recovery route

## Mobile behaviour

The current organisation, urgent action and primary button remain visible without relying on hover, wide tables or tiny status text.

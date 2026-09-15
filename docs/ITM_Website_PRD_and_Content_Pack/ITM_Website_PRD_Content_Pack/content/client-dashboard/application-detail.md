# Application Detail

**Audience:** Authenticated client users with organisation-scoped permissions  
**Indexing:** Noindex, authenticated  

## Purpose

Show progress timeline, requirements, document requests, messages, quotation link, payment state and deliverables without internal notes.

## Page content

- Persistent organisation switcher with organisation name and user role.
- Plain-language status and next action.
- Relevant records only for organisations the user can access.
- Help text that explains what ITM is waiting for and what happens next.
- Primary action positioned near the item that requires attention.

## Page-specific information

- Progress timeline
- Next action
- Requirements
- Uploaded documents
- Messages
- Payment summary
- Deliverables

## Actions to design

- Upload or replace file
- Confirm information
- Approve submission data
- Send message
- Download final document

## Permission behaviour

Owners, Administrators, Contributors and Viewers see only actions granted to their role. Binding quotation approval, member administration, payment information and document access are independently permissioned.

## States

Loading · Empty with useful next step · Action required · Under review · Completed · Permission limited · Error with recovery route

## Mobile behaviour

The current organisation, urgent action and primary button remain visible without relying on hover, wide tables or tiny status text.

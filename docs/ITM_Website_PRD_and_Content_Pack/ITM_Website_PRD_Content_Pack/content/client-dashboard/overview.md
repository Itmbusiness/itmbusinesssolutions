# Client Overview

**Audience:** Authenticated client users with organisation-scoped permissions  
**Indexing:** Noindex, authenticated  

## Purpose

Summarise all permitted organisations, applications requiring action, quotations awaiting decision, payments, expiring documents and upcoming compliance dates.

## Page content

- Persistent organisation switcher with organisation name and user role.
- Plain-language status and next action.
- Relevant records only for organisations the user can access.
- Help text that explains what ITM is waiting for and what happens next.
- Primary action positioned near the item that requires attention.

## Page-specific information

- Organisation switcher
- Actions required
- Open applications
- Quotes awaiting decision
- Payment status
- Upcoming deadlines

## Actions to design

- Open required action
- Request service
- Contact consultant

## Permission behaviour

Owners, Administrators, Contributors and Viewers see only actions granted to their role. Binding quotation approval, member administration, payment information and document access are independently permissioned.

## States

Loading · Empty with useful next step · Action required · Under review · Completed · Permission limited · Error with recovery route

## Mobile behaviour

The current organisation, urgent action and primary button remain visible without relying on hover, wide tables or tiny status text.

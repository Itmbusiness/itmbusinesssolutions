# Conceptual Data Model

## Identity and access

- User
- StaffProfile
- ClientProfile
- Role
- Permission
- OrganisationMembership
- Invitation
- Session and security event

## CRM and commercial

- Lead
- LeadSource
- Enquiry
- RecommendationResponse
- Quotation
- QuotationVersion
- QuotationLineItem
- QuotationDecision
- PaymentRequest
- Payment
- Receipt

## Organisation and compliance

- Organisation
- OrganisationType
- OrganisationIdentifier
- Address
- Contact
- RegistrationRecord
- ComplianceObligation
- ComplianceEvent
- ReminderRule

## Service delivery

- ServiceCategory
- Service
- Package
- RequirementTemplate
- RequirementItem
- Application
- ApplicationStatusHistory
- Task
- Assignment
- Review
- Document
- DocumentVersion
- DocumentRequest
- MessageThread
- Message

## Content

- Page
- Article
- ArticleCategory
- Author
- ContentReview
- Redirect
- FAQ
- SEORecord

## Governance fields

Material records include creator, timestamps, organisation scope, status, version, last editor and audit reference. Regulated content also includes source links, reviewer, reviewed-on and next-review dates.

## Critical relationships

- A user has many organisation memberships.
- An organisation has many members, applications, documents and compliance events.
- A quotation can contain several services and create several applications after approval and payment.
- Documents may satisfy requirements across applications only when access and validity rules allow it.
- A service owns a versioned requirement template and related content page.

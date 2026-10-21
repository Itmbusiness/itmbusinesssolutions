<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change only imported wording that still exactly matches the old pack.
        // Owner-edited CMS records keep their current content.
        $this->replaceBody('pages', 'about', 'Production content must insert the verified legal entity name, registration number, consultant authorisation where applicable, physical address, contact information and real team profiles.', 'For current contact details and office information, see our [Contact page](/contact). We can provide the business details relevant to your enquiry before you engage us. We publish team credentials only after they have been verified.');

        foreach ([
            ['pbc-vs-private-limited-company-zimbabwe', 'Explain ownership terminology, governance, administrative expectations and perceived suitability using current reviewed information. Avoid claiming that one structure is automatically better for every founder.', 'A PBC and a private limited company use different ownership and management arrangements. Compare who will own the business, who will make decisions, what records you will need to maintain and what future investors or contracting parties may expect. Neither structure is automatically right for every founder; the suitable route depends on the proposed activities and the current registry requirements.'],
            ['pbc-vs-private-limited-company-zimbabwe', 'Verify current registry rules with the responsible Companies Registry before publication.', 'Confirm the current filing requirements with the responsible Companies Registry before submitting an application.'],
            ['documents-needed-company-registration-zimbabwe', 'Verify current registry rules with the responsible Companies Registry before publication.', 'Confirm the current filing requirements with the responsible Companies Registry before submitting an application.'],
            ['what-to-do-after-registering-company-zimbabwe', 'Verify current registry rules with the responsible Companies Registry before publication.', 'Confirm the current filing requirements with the responsible Companies Registry before submitting an application.'],
        ] as [$slug, $old, $new]) {
            $this->replaceBody('articles', $slug, $old, $new);
        }

        foreach ([
            ['Availability, deadlines and consequences must be checked against a current official notice. The page should display a dated status banner maintained by the content team.', 'Please ask us to check the current re-registration window and the requirements for your existing records before you pay. Deadlines and consequences can change through official notices.'],
            ['Validity must be confirmed from the current certificate and official guidance. The dashboard should store the issue and expiry dates.', 'Check the issue and expiry dates on your current certificate. We can help you review its status against current official guidance.'],
            ['The quotation must state whether ITM Business Solutions supplies, resells or only assists with an approved third-party solution.', 'We will clarify in your written quotation whether we can help you select a provider, assist with setup or supply a solution for your specific needs.'],
            ['Credentials must never be requested in ordinary chat. The platform should use secure, time-limited collection and access controls.', 'Do not send passwords or one-time codes in ordinary chat. We will explain any access needed for the agreed work through an appropriate private channel.'],
            ['The package should clearly list included source and export formats.', 'Your written quotation will list the editable source files and export formats included in your package.'],
        ] as [$old, $new]) {
            DB::table('faqs')->where('answer', $old)->update(['answer' => $new]);
        }

        foreach ([
            'paye-vat-qpd-income-tax-explained' => ['A practical guide to paye, vat, qpd and income tax: a practical introduction, including preparation questions, common risks and the next appropriate step.', 'Understand how PAYE, VAT, QPDs and income tax relate to a Zimbabwean business, and what records to prepare before seeking tax support.'],
            'documents-needed-company-registration-zimbabwe' => ['A practical guide to documents and information needed for company registration, including preparation questions, common risks and the next appropriate step.', 'Prepare names, ownership details, identity records and addresses before starting a company registration enquiry in Zimbabwe.'],
            'questions-before-family-trust' => ['A practical guide to questions to answer before forming a family trust, including preparation questions, common risks and the next appropriate step.', 'Explore the purpose, trustees, beneficiaries and assets to consider before seeking advice on a family trust in Zimbabwe.'],
            'praz-and-vendor-number-difference' => ['A practical guide to praz registration and vendor numbers: what is the difference?, including preparation questions, common risks and the next appropriate step.', 'Understand the difference between PRAZ supplier registration and a buyer-specific vendor number before preparing a tender application.'],
            'trust-pvo-association-differences' => ['A practical guide to trust, pvo or association: which structure fits the purpose?, including preparation questions, common risks and the next appropriate step.', 'Compare the questions to ask when considering a trust, PVO or association for an organisation in Zimbabwe.'],
            'pbc-vs-private-limited-company-zimbabwe' => ['A practical guide to pbc vs private limited company in zimbabwe, including preparation questions, common risks and the next appropriate step.', 'Compare PBC and private limited company structures in Zimbabwe by ownership, governance and future plans before requesting registration support.'],
            'what-is-a-tin-zimbabwe' => ['A practical guide to what is a tin and why does a business need one?, including preparation questions, common risks and the next appropriate step.', 'Learn what a Taxpayer Identification Number is used for in Zimbabwe and what to prepare before asking for ZIMRA registration support.'],
            'what-to-do-after-registering-company-zimbabwe' => ['A practical guide to what to do after registering a company in zimbabwe, including preparation questions, common risks and the next appropriate step.', 'Review the practical next steps after company registration in Zimbabwe, from checking records to planning tax and employer obligations.'],
            'what-is-nssa-p4-return' => ['A practical guide to what is an nssa p4 return?, including preparation questions, common risks and the next appropriate step.', 'Understand the purpose of an NSSA P4 return and the payroll records an employer may need before preparing a submission.'],
            'when-employer-register-nssa' => ['A practical guide to when must an employer register with nssa?, including preparation questions, common risks and the next appropriate step.', 'Find out what to check when a Zimbabwean business becomes an employer and needs to plan NSSA registration.'],
            'tender-readiness-document-checklist' => ['A practical guide to tender-readiness document checklist for zimbabwean businesses, including preparation questions, common risks and the next appropriate step.', 'Use a practical checklist to prepare company, tax and supplier records before responding to a tender in Zimbabwe.'],
            'why-tax-clearance-may-be-delayed' => ['A practical guide to why a tax clearance request may be delayed, including preparation questions, common risks and the next appropriate step.', 'Review common record and filing issues that may delay a ZIMRA tax clearance request, and prepare for a focused assessment.'],
        ] as $slug => [$old, $new]) {
            DB::table('articles')->where('slug', $slug)->where('meta_description', $old)
                ->update(['meta_description' => $new]);
            DB::table('articles')->where('slug', $slug)->where('excerpt', $old)
                ->update(['excerpt' => $new]);
        }

        DB::table('articles')->where('author', '[Named author]')->update(['author' => null]);
        DB::table('articles')->where('reviewer', '[Authorised reviewer]')->update(['reviewer' => null]);
        DB::table('services')->where('reviewer', '[Authorised reviewer]')->update(['reviewer' => null]);
    }

    public function down(): void
    {
        // Editorial changes cannot be safely reverted over later owner edits.
    }

    private function replaceBody(string $table, string $slug, string $old, string $new): void
    {
        $row = DB::table($table)->where('slug', $slug)->first(['id', 'body']);

        if ($row && str_contains($row->body ?? '', $old)) {
            DB::table($table)->where('id', $row->id)->update([
                'body' => str_replace($old, $new, $row->body),
            ]);
        }
    }
};

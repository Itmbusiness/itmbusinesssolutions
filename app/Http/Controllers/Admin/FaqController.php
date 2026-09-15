<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.faqs.index', [
            'globalFaqs' => Faq::global()->orderBy('position')->get(),
            'serviceFaqs' => Faq::whereNotNull('faqable_type')
                ->with('faqable')
                ->orderBy('faqable_id')
                ->orderBy('position')
                ->get()
                ->groupBy(fn (Faq $faq): string => $faq->faqable instanceof Service
                    ? $faq->faqable->name
                    : __('Unattached')),
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.faqs.form', [
            'faq' => new Faq(['is_published' => true]),
            'services' => Service::ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Faq::create($this->validated($request));

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ created.');
    }

    public function edit(Faq $faq): View
    {
        return view('pages.admin.faqs.form', [
            'faq' => $faq,
            'services' => Service::ordered()->get(),
        ]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validated($request));

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ saved.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:5000'],
            'service_id' => ['nullable', 'exists:services,id'],
            'group' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $serviceId = $validated['service_id'] ?? null;
        unset($validated['service_id']);

        // No service means a site-wide FAQ shown on /faq.
        $validated['faqable_type'] = $serviceId ? Service::class : null;
        $validated['faqable_id'] = $serviceId;
        $validated['is_published'] = $request->boolean('is_published');
        $validated['position'] ??= 0;

        return $validated;
    }
}

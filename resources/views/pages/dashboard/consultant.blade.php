<div class="page-shell py-8">
    <header>
        <p class="text-eyebrow uppercase text-teal">Back office</p>
        <h1 class="mt-2 text-h2">{{ __('Overview') }}</h1>
    </header>

    {{--
        Design system §08, consultant variant. Leads, applications and clients
        aren't built yet, so — same rule as the client dashboard — these read
        as empty placeholders, never invented figures.
    --}}
    <div class="mt-6 grid gap-3.5 md:grid-cols-3">
        <x-ui.card kicker="Leads" title="New this week">
            <p class="mt-1 text-h1 tabular-nums">—</p>
            <p class="card-meta mt-1">No leads yet</p>
        </x-ui.card>

        <x-ui.card kicker="Quotations" title="Awaiting review">
            <p class="mt-1 text-h1 tabular-nums">—</p>
            <p class="card-meta mt-1">Nothing to review</p>
        </x-ui.card>

        <x-ui.card navy kicker="Client actions" title="Overdue">
            <p class="mt-1 text-h1 tabular-nums">—</p>
            <p class="mt-1 text-meta text-navy-300">Nothing overdue</p>
        </x-ui.card>
    </div>

    <x-ui.alert class="mt-3.5" variant="info" title="Leads, clients and applications are on the way">
        This back office will prioritise new leads, quotations awaiting your review,
        and client actions that are overdue — as those workflows are built.
    </x-ui.alert>

    <section class="mt-8" aria-labelledby="dash-tasks">
        <h2 id="dash-tasks" class="text-h3">{{ __('My tasks') }}</h2>

        <x-ui.empty-state class="mt-3.5" icon="check-circle" title="No tasks assigned yet">
            Tasks a client's request needs from you will appear here once the
            leads and applications workflow is in place.
        </x-ui.empty-state>
    </section>
</div>

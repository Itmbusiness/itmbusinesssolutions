<x-layouts::admin :title="__('Overview')">
    <header>
        <p class="text-eyebrow uppercase text-teal">{{ __('Admin') }}</p>
        <h1 class="mt-2 text-h2">{{ __('Work overview') }}</h1>
        <p class="mt-2 text-muted">{{ __('Lead follow-up and content requiring review.') }}</p>
    </header>

    <div class="mt-6 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            [__('Awaiting response'), $awaitingResponseCount, __('Open leads without a recorded reply'), route('admin.leads.index', ['response' => 'waiting'])],
            [__('Open leads'), $openLeads, __(':count new', ['count' => $newLeads]), route('admin.leads.index')],
            [__('Converted leads'), $convertedLeads, __('Of :total leads, all time', ['total' => $totalLeads]), route('admin.leads.index')],
            [__('Articles due review'), $staleArticles, __('Of :total articles', ['total' => $articleCount]), route('admin.articles.index')],
        ] as [$label, $value, $note, $route])
            <x-ui.dashboard-stat :label="$label" :value="$value" :note="$note" :href="$route" />
        @endforeach
    </div>

    <section class="mt-8" aria-labelledby="awaiting-response">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h2 id="awaiting-response" class="text-h3">{{ __('No response recorded') }} <span class="text-muted">({{ $awaitingResponseCount }})</span></h2>
            <a href="{{ route('admin.leads.index', ['response' => 'waiting']) }}" class="text-meta font-semibold text-teal">{{ __('View response queue') }} &rarr;</a>
        </div>

        @if ($awaitingResponseLeads->isEmpty())
            <x-ui.empty-state class="mt-4" :title="__('All open requests have a recorded response')">
                {{ __('New enquiries with no recorded human reply will appear here.') }}
            </x-ui.empty-state>
        @else
            <x-ui.table class="mt-4" :caption="__('Oldest open requests with no response recorded')">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Reference') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Owner') }}</th>
                        <th scope="col">{{ __('Waiting') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($awaitingResponseLeads as $lead)
                        <tr>
                            <th scope="row" class="font-mono font-normal"><a href="{{ route('admin.leads.show', $lead) }}">{{ $lead->reference }}</a></th>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->assignee?->name ?? __('Unassigned') }}</td>
                            <td>{{ $lead->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
        @endif
    </section>

    <section class="mt-8" aria-labelledby="recent-leads">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h2 id="recent-leads" class="text-h3">{{ __('Recent requests') }}</h2>
            <a href="{{ route('admin.leads.index') }}" class="text-meta font-semibold text-teal">{{ __('All leads') }} &rarr;</a>
        </div>

        @if ($recentLeads->isEmpty())
            <x-ui.empty-state class="mt-4" :title="__('No requests yet')">
                {{ __('Quotation requests from the website will appear here.') }}
            </x-ui.empty-state>
        @else
            <x-ui.table class="mt-4" :caption="__('Recent requests')">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Reference') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Service') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Received') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentLeads as $lead)
                        <tr>
                            <th scope="row" class="font-mono font-normal">
                                <a href="{{ route('admin.leads.show', $lead) }}">{{ $lead->reference }}</a>
                            </th>
                            <td>{{ $lead->name }}</td>
                            <td class="text-muted">{{ $lead->service?->name ?? __('Not specified') }}</td>
                            <td><x-ui.badge :variant="$lead->status->badge()">{{ $lead->status->label() }}</x-ui.badge></td>
                            <td class="text-muted">{{ $lead->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
        @endif
    </section>
</x-layouts::admin>

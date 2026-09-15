<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => 'Design system'])
</head>
<body>
    <div class="page-shell pb-24">
        <header class="border-b border-line py-11">
            <p class="text-eyebrow text-teal">Reference board</p>
            <h1 class="mt-3 text-h1">ITM Business Solutions</h1>
            <p class="mt-3 max-w-[56ch] text-lead text-muted">
                Every token and component below is rendered from
                <code>resources/css/app.css</code> and the <code>x-ui.*</code> Blade
                components — not from copied markup. If this page looks right, the
                system is wired up.
            </p>
        </header>

        <section class="mt-16" aria-labelledby="ds-colour">
            <h2 id="ds-colour" class="text-eyebrow border-b border-line pb-3 text-muted">01 — Colour roles</h2>

            <div class="mt-5 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Deep navy', '#152A45', 'bg-navy', 'Authority. Sidebar, footer, headings, section grounds.'],
                    ['Teal blue', '#247BA0', 'bg-teal', 'Every primary action, link and active state. One per view.'],
                    ['Warm off-white', '#F4F7F9', 'bg-page', 'Page ground. Cards sit on it in #FFFFFF.'],
                    ['Restrained gold', '#D79B2E', 'bg-gold', 'Small highlights only — one badge, never a fill.'],
                ] as [$name, $hex, $swatch, $note])
                    <x-ui.card>
                        <div class="{{ $swatch }} mb-3 h-14 rounded-control border border-line"></div>
                        <p class="font-semibold">{{ $name }}</p>
                        <p class="card-meta font-mono">{{ $hex }}</p>
                        <p class="card-body mt-2">{{ $note }}</p>
                    </x-ui.card>
                @endforeach
            </div>

            <div class="mt-3.5 grid gap-3.5 sm:grid-cols-2">
                @foreach ([
                    ['Navy ramp', ['bg-navy-900', 'bg-navy-800', 'bg-navy-700', 'bg-navy-600', 'bg-navy-500', 'bg-navy-300', 'bg-navy-100'], [900, 800, 700, 600, 500, 300, 100]],
                    ['Teal ramp', ['bg-teal-900', 'bg-teal-800', 'bg-teal-700', 'bg-teal-500', 'bg-teal-400', 'bg-teal-300', 'bg-teal-100'], [900, 800, 700, 500, 400, 300, 100]],
                ] as [$label, $steps, $numbers])
                    <x-ui.card>
                        <p class="mb-3.5 font-semibold">{{ $label }}</p>
                        <div class="flex overflow-hidden rounded-control">
                            @foreach ($steps as $step)
                                <div class="{{ $step }} h-14 flex-1"></div>
                            @endforeach
                        </div>
                        <div class="mt-2 flex font-mono text-meta text-muted">
                            @foreach ($numbers as $number)
                                <div class="flex-1">{{ $number }}</div>
                            @endforeach
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        </section>

        <section class="mt-16" aria-labelledby="ds-type">
            <h2 id="ds-type" class="text-eyebrow border-b border-line pb-3 text-muted">02 — Type scale (Inter)</h2>

            <x-ui.card class="mt-5 space-y-6">
                <div>
                    <p class="card-meta font-mono">56 / 600 / -.02em</p>
                    <p class="text-display">Display</p>
                </div>
                <div>
                    <p class="card-meta font-mono">40 / 600</p>
                    <p class="text-h1">Page heading H1</p>
                </div>
                <div>
                    <p class="card-meta font-mono">28 / 600</p>
                    <p class="text-h2">Section heading H2</p>
                </div>
                <div>
                    <p class="card-meta font-mono">20 / 600</p>
                    <p class="text-h3">Card title H3</p>
                </div>
                <div>
                    <p class="card-meta font-mono">17 / 400 / 1.6</p>
                    <p class="text-lead">Body large — hero support and article lead. Sits at 1.6 line height so long compliance copy stays readable on a phone.</p>
                </div>
                <div>
                    <p class="card-meta font-mono">15 / 400 / 1.6</p>
                    <p>Body — the default. Interface copy, table cells, form values and helper paragraphs.</p>
                </div>
                <div>
                    <p class="card-meta font-mono">13 / 400</p>
                    <p class="text-meta">Meta — reviewed dates, helper text, table headers. Floor for body; never smaller.</p>
                </div>
                <div>
                    <p class="card-meta font-mono">11 / 600 / .12em</p>
                    <p class="text-eyebrow uppercase">Eyebrow label</p>
                </div>
            </x-ui.card>
        </section>

        <section class="mt-16" aria-labelledby="ds-buttons">
            <h2 id="ds-buttons" class="text-eyebrow border-b border-line pb-3 text-muted">04 — Buttons &amp; badges</h2>

            <x-ui.card class="mt-5">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="flex flex-col items-start gap-3">
                        <p class="text-eyebrow uppercase text-muted">Primary</p>
                        <x-ui.button>Request a Quotation</x-ui.button>
                        <x-ui.button disabled>Disabled</x-ui.button>
                        <x-ui.button icon="circle-notch">Sending…</x-ui.button>
                    </div>

                    <div class="flex flex-col items-start gap-3">
                        <p class="text-eyebrow uppercase text-muted">Secondary — outlined</p>
                        <x-ui.button variant="secondary">View Service</x-ui.button>
                        <x-ui.button variant="quiet">Save Draft</x-ui.button>
                        <x-ui.button variant="ghost" icon-trailing="arrow-right">Track Application</x-ui.button>
                    </div>

                    <div class="flex flex-col items-start gap-3">
                        <p class="text-eyebrow uppercase text-muted">WhatsApp — restrained</p>
                        <x-ui.button variant="whatsapp" icon="whatsapp-logo">Chat on WhatsApp</x-ui.button>
                        <div class="flex gap-2">
                            <x-ui.button variant="icon" icon="download-simple" aria-label="Download" />
                            <x-ui.button variant="icon" icon="paperclip" aria-label="Attach a file" />
                            <x-ui.button variant="icon" icon="dots-three" aria-label="More actions" />
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-line-soft">

                <p class="text-eyebrow mb-3.5 uppercase text-muted">Status badges — colour plus icon plus text, never colour alone</p>
                <div class="flex flex-wrap gap-2.5">
                    <x-ui.badge variant="info">Awaiting Decision</x-ui.badge>
                    <x-ui.badge variant="success">Approved</x-ui.badge>
                    <x-ui.badge variant="attention">Client Action Required</x-ui.badge>
                    <x-ui.badge variant="problem">Expired</x-ui.badge>
                    <x-ui.badge variant="neutral">Draft</x-ui.badge>
                    <x-ui.badge variant="featured">Most Requested</x-ui.badge>
                </div>
            </x-ui.card>
        </section>

        <section class="mt-16" aria-labelledby="ds-forms">
            <h2 id="ds-forms" class="text-eyebrow border-b border-line pb-3 text-muted">05 — Forms</h2>

            <x-ui.card class="mt-5 space-y-6">
                <x-ui.field
                    label="Organisation name"
                    for="ds-org"
                    hint="If you have not registered yet, use your preferred name."
                >
                    <x-ui.input id="ds-org" aria-describedby="ds-org-hint" placeholder="As it should appear on the register" />
                </x-ui.field>

                <x-ui.field
                    label="Taxpayer identifier"
                    for="ds-tin"
                    error="This does not look like a valid identifier. Leave blank if unsure."
                >
                    <x-ui.input id="ds-tin" value="0000" invalid aria-describedby="ds-tin-error" />
                </x-ui.field>

                <x-ui.field label="What is your goal?" for="ds-goal">
                    <x-ui.select id="ds-goal">
                        <option>Register a new company</option>
                        <option>Become tax compliant</option>
                        <option>Prepare for tenders</option>
                        <option>I am not sure</option>
                    </x-ui.select>
                </x-ui.field>

                <x-ui.field legend="Preferred channel">
                    <div class="flex flex-wrap gap-2">
                        <x-ui.choice name="ds-channel" value="whatsapp" checked>WhatsApp</x-ui.choice>
                        <x-ui.choice name="ds-channel" value="email">Email</x-ui.choice>
                        <x-ui.choice name="ds-channel" value="phone">Phone call</x-ui.choice>
                    </div>
                </x-ui.field>
            </x-ui.card>
        </section>

        <section class="mt-16" aria-labelledby="ds-progress">
            <h2 id="ds-progress" class="text-eyebrow border-b border-line pb-3 text-muted">06 — Progress</h2>

            <x-ui.card class="mt-5">
                <x-ui.stepper
                    :steps="['Request', 'Assessment', 'Quote approval', 'Payment', 'Application']"
                    :current="3"
                />
            </x-ui.card>
        </section>

        <section class="mt-16" aria-labelledby="ds-table">
            <h2 id="ds-table" class="text-eyebrow border-b border-line pb-3 text-muted">07 — Data table</h2>

            <x-ui.table class="mt-5" caption="Applications in progress">
                <thead>
                    <tr>
                        <th scope="col">Reference</th>
                        <th scope="col">Organisation</th>
                        <th scope="col">Service</th>
                        <th scope="col">Status</th>
                        <th scope="col">Next action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['APP-2418', 'Sunrise Logistics (Pvt) Ltd', 'PRAZ Registration Support', 'attention', 'Client action', 'Upload tax clearance'],
                        ['APP-2411', 'Chenai Family Trust', 'Trust Registration', 'info', 'In progress', 'ITM preparing submission'],
                        ['APP-2402', 'Mbare Hardware PBC', 'NSSA Employer Registration', 'success', 'Completed', 'Deliverables available'],
                    ] as [$ref, $org, $service, $variant, $status, $next])
                        <tr>
                            <th scope="row" class="font-mono font-normal">{{ $ref }}</th>
                            <td>{{ $org }}</td>
                            <td>{{ $service }}</td>
                            <td><x-ui.badge :variant="$variant">{{ $status }}</x-ui.badge></td>
                            <td class="text-muted">{{ $next }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
        </section>

        <section class="mt-16" aria-labelledby="ds-alerts">
            <h2 id="ds-alerts" class="text-eyebrow border-b border-line pb-3 text-muted">09 — Alerts &amp; empty states</h2>

            <div class="mt-5 flex flex-col gap-3">
                <x-ui.alert variant="info" title="Requirements may change after assessment">
                    This checklist helps you prepare. A consultant confirms the final list in your quotation.
                </x-ui.alert>
                <x-ui.alert variant="attention" title="This quotation expires in 3 days">
                    Approve version 2 before 12 September 2026, or request an updated quote.
                </x-ui.alert>
                <x-ui.alert variant="problem" title="A document was rejected">
                    Page 2 of your ID scan is unreadable. Replace it to continue — nothing else is affected.
                </x-ui.alert>
                <x-ui.alert variant="success" title="Quotation approved — reference QUO-1183 v2">
                    Payment instructions are now available. ITM will not ask for passwords or one-time codes.
                </x-ui.alert>
            </div>

            <div class="mt-3.5 grid gap-3.5 sm:grid-cols-2">
                <x-ui.empty-state title="No applications yet">
                    When you request a service for this organisation, it will appear here with its progress.

                    <x-slot:action>
                        <x-ui.button>Request a Service</x-ui.button>
                    </x-slot:action>
                </x-ui.empty-state>

                <x-ui.empty-state icon="lock-simple" title="You do not have access to payments">
                    Your role in Sunrise Logistics is Contributor. An owner can change this.

                    <x-slot:action>
                        <x-ui.button variant="secondary">Request access</x-ui.button>
                    </x-slot:action>
                </x-ui.empty-state>
            </div>
        </section>
    </div>
</body>
</html>

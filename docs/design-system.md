# ITM design system

The source of truth is `docs/Waiting on screen selections/ITM Design System.dc.html`
(prompt 01, "Master visual system"). Open it in a browser to see the original board.

The `_ds/nocturne-*` folder shipped alongside it is **not** the ITM system. The board
says so explicitly: Nocturne is a dark blurple interface, incompatible with ITM's
gold-on-navy mark and the off-white page rule. ITM's palette wins; what we inherited
from Nocturne is its *method* — Inter, tonal ramps, a compact spacing scale, outlined
secondary actions, themed focus rings, Phosphor icons.

## Where it lives

| Layer | File |
| --- | --- |
| Tokens (`@theme`) and component classes | `resources/css/app.css` |
| Blade components | `resources/views/components/ui/*.blade.php` |
| Living reference board | `/design-system` (non-production only) |
| Tests | `tests/Feature/DesignSystemTest.php` |

Screens already on the system: the public homepage (`welcome.blade.php`), the
workspace shell and sidebar (`layouts/app/sidebar.blade.php`), the dashboard, and
the log-in, register and forgot-password screens.

The workspace sidebar keeps Flux for its behaviour (sticky, mobile collapse) and
takes ITM's navy through the `.sidebar-navy` class. Two dark-ground helpers exist
for that context: `.card-navy` and `.btn.on-navy`, since a teal outline loses
contrast on navy.

Inter is loaded through `vite.config.js` (Bunny fonts, weights 400/500/600).
Phosphor is the icon set, installed as `@phosphor-icons/web` and imported at the
top of `app.css`.

## Rules the board is explicit about

- **One primary action per view.** `<x-ui.button>` defaults to the filled teal
  primary; everything else is `secondary`, `quiet`, `ghost`, `whatsapp` or `icon`.
- **Status is colour *plus* icon *plus* text, never colour alone.** `<x-ui.badge>`
  ships a default glyph for every variant, and a test enforces it.
- **Gold is a highlight, never a fill.** It appears on `badge-featured`'s outline
  and nowhere else.
- **13px is the floor for body text.** `--text-meta` is the smallest scale step.
- **48px minimum touch target** on buttons, inputs and choice tiles.
- **Focus is themed, never the browser default** — a 2px teal ring at 2px offset.
- **No invented facts.** The board keeps fees, authority logos, seals and processing
  guarantees as visible placeholders. Keep it that way in real screens.

## Using it

```blade
<x-ui.button icon="paper-plane-tilt">Request a Quotation</x-ui.button>
<x-ui.button variant="secondary" href="/services">View Service</x-ui.button>

<x-ui.badge variant="attention">Client Action Required</x-ui.badge>

<x-ui.alert variant="problem" title="A document was rejected">
    Page 2 of your ID scan is unreadable.
</x-ui.alert>

<x-ui.field label="Organisation name" for="org" hint="Use your preferred name.">
    <x-ui.input id="org" aria-describedby="org-hint" />
</x-ui.field>

<x-ui.stepper :steps="['Request', 'Assessment', 'Quote approval']" :current="2" />
```

Prefer these classes and components over ad-hoc Tailwind colour utilities. If a
screen needs something the board does not cover, add it here first so the board and
the code stay in step.

## A note on `teal-*`

Tailwind ships its own green-cyan `teal` palette. `app.css` clears it
(`--color-teal-*: initial`) before defining ITM's seven steps, so an unspecified
step such as `bg-teal-600` produces nothing rather than quietly rendering the
wrong hue.

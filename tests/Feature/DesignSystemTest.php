<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class DesignSystemTest extends TestCase
{
    public function test_the_reference_board_renders_outside_production(): void
    {
        // The board is a full page, so it pulls in the Vite bundle; the tests
        // assert markup, not built assets.
        $this->withoutVite();

        $response = $this->get(route('design-system'));

        $response->assertOk();
        $response->assertSee('ITM Business Solutions');
    }

    public function test_buttons_default_to_the_filled_teal_primary(): void
    {
        $html = Blade::render('<x-ui.button>Request a Quotation</x-ui.button>');

        $this->assertStringContainsString('class="btn btn-primary"', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertStringContainsString('Request a Quotation', $html);
    }

    public function test_buttons_render_as_anchors_when_given_an_href(): void
    {
        $html = Blade::render('<x-ui.button variant="secondary" href="/services">View Service</x-ui.button>');

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/services"', $html);
        $this->assertStringContainsString('btn-secondary', $html);
        $this->assertStringNotContainsString('type="button"', $html);
    }

    /**
     * The board is explicit that status is "colour plus icon plus text, never
     * colour alone", so a variant must not be able to render without a glyph.
     */
    public function test_status_badges_always_pair_colour_with_an_icon(): void
    {
        foreach (['info', 'success', 'attention', 'problem', 'neutral'] as $variant) {
            $html = Blade::render(
                '<x-ui.badge :variant="$variant">Status</x-ui.badge>',
                ['variant' => $variant]
            );

            $this->assertStringContainsString("badge-{$variant}", $html);
            $this->assertStringContainsString('ph-fill', $html, "The [{$variant}] badge rendered without an icon.");
        }
    }

    public function test_the_gold_featured_badge_is_an_outline_without_an_icon(): void
    {
        $html = Blade::render('<x-ui.badge variant="featured">Most Requested</x-ui.badge>');

        $this->assertStringContainsString('badge-featured', $html);
        $this->assertStringNotContainsString('ph-fill', $html);
    }

    public function test_alerts_announce_problems_and_attention_to_assistive_technology(): void
    {
        $problem = Blade::render('<x-ui.alert variant="problem" title="Rejected">Replace it.</x-ui.alert>');
        $info = Blade::render('<x-ui.alert variant="info" title="Heads up">Prepare.</x-ui.alert>');

        $this->assertStringContainsString('role="alert"', $problem);
        $this->assertStringContainsString('role="status"', $info);
    }

    public function test_a_field_labels_its_control_and_exposes_its_error(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-ui.field label="Taxpayer identifier" for="tin" error="That is not a valid identifier.">
                <x-ui.input id="tin" invalid aria-describedby="tin-error" />
            </x-ui.field>
        BLADE);

        $this->assertStringContainsString('for="tin"', $html);
        $this->assertStringContainsString('id="tin-error"', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
    }

    public function test_a_radio_group_is_wrapped_in_a_fieldset_with_a_legend(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-ui.field legend="Preferred channel">
                <x-ui.choice name="channel" value="whatsapp" checked>WhatsApp</x-ui.choice>
                <x-ui.choice name="channel" value="email">Email</x-ui.choice>
            </x-ui.field>
        BLADE);

        $this->assertStringContainsString('<fieldset', $html);
        $this->assertStringContainsString('<legend class="field-label">Preferred channel</legend>', $html);
        $this->assertStringContainsString('checked', $html);
    }

    public function test_the_stepper_marks_the_current_step_and_offers_a_mobile_summary(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-ui.stepper :steps="['Request', 'Assessment', 'Quote approval', 'Payment', 'Application']" :current="3" />
        BLADE);

        $this->assertStringContainsString('aria-current="step"', $html);
        $this->assertStringContainsString('step-done', $html);
        $this->assertStringContainsString('Step 3 of 5', $html);
    }

    public function test_icons_are_hidden_from_assistive_technology_unless_labelled(): void
    {
        $decorative = Blade::render('<x-ui.icon name="clock" />');
        $labelled = Blade::render('<x-ui.icon name="clock" aria-label="Pending" />');

        $this->assertStringContainsString('aria-hidden="true"', $decorative);
        $this->assertStringContainsString('aria-hidden="false"', $labelled);
    }
}

<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use App\Support\PublicMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicImageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public_images');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => UserRole::Admin]);
    }

    public function test_settings_shows_all_default_previews_and_upload_controls(): void
    {
        $response = $this->actingAs($this->admin())->get(route('admin.settings.edit'))->assertOk();
        foreach (PublicMedia::SUBJECTS as $subject => $label) {
            $response->assertSee('images/public/'.$subject.'-v1.webp')
                ->assertSee(route('admin.images.update', $subject));
        }
        $response->assertSee('enctype="multipart/form-data"', false)
            ->assertDontSee('Local image path');
    }

    public function test_an_admin_can_upload_replace_remove_and_restore_an_image(): void
    {
        $this->actingAs($this->admin());
        $this->put(route('admin.images.update', 'registration'), [
            'image' => UploadedFile::fake()->image('photo.jpg', 600, 400),
        ])->assertRedirect(route('admin.settings.edit'))->assertSessionHasNoErrors();
        $firstPath = Setting::get('media.registration');
        Storage::disk('public_images')->assertExists(basename($firstPath));
        $this->get(route('home'))->assertSee($firstPath);

        $this->put(route('admin.images.update', 'registration'), [
            'image' => UploadedFile::fake()->image('replacement.png', 500, 300),
        ])->assertSessionHasNoErrors();
        $secondPath = Setting::get('media.registration');
        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('public_images')->assertMissing(basename($firstPath));
        Storage::disk('public_images')->assertExists(basename($secondPath));

        $this->delete(route('admin.images.destroy', 'registration'))->assertRedirect();
        Storage::disk('public_images')->assertMissing(basename($secondPath));
        $this->assertNull(PublicMedia::path('registration'));
        $this->get(route('home'))->assertDontSee($secondPath)->assertDontSee('images/public/registration-v1.webp');

        $this->post(route('admin.images.restore', 'registration'))->assertRedirect();
        $this->assertSame('images/public/registration-v1.webp', PublicMedia::path('registration'));
        $this->get(route('home'))->assertSee('images/public/registration-v1.webp');
    }

    public function test_clients_cannot_change_public_images(): void
    {
        $this->actingAs(User::factory()->create());
        $this->put(route('admin.images.update', 'tax'), ['image' => UploadedFile::fake()->image('tax.jpg')])->assertForbidden();
        $this->delete(route('admin.images.destroy', 'tax'))->assertForbidden();
        $this->post(route('admin.images.restore', 'tax'))->assertForbidden();
    }

    public function test_invalid_and_oversized_uploads_preserve_the_current_image(): void
    {
        $this->actingAs($this->admin());
        $this->put(route('admin.images.update', 'tax'), ['image' => UploadedFile::fake()->create('script.php', 1, 'text/plain')])->assertSessionHasErrors('image');
        $this->put(route('admin.images.update', 'tax'), ['image' => UploadedFile::fake()->image('large.jpg')->size(9000)])->assertSessionHasErrors('image');
        $this->assertNull(Setting::get('media.tax'));
        $this->assertSame([], Storage::disk('public_images')->allFiles());
    }

    public function test_unknown_subjects_cannot_create_arbitrary_settings(): void
    {
        $this->actingAs($this->admin());
        $this->put(route('admin.images.update', 'unknown'), ['image' => UploadedFile::fake()->image('valid.jpg')])->assertNotFound();
        $this->delete(route('admin.images.destroy', 'unknown'))->assertNotFound();
        $this->post(route('admin.images.restore', 'unknown'))->assertNotFound();
    }

    public function test_removing_defaults_never_deletes_the_original_generated_files(): void
    {
        $this->actingAs($this->admin())->delete(route('admin.images.destroy', 'consultation'))->assertRedirect();
        $this->assertFileExists(public_path('images/public/consultation-v1.webp'));
        $this->assertNull(PublicMedia::path('consultation'));
        $this->post(route('admin.images.restore', 'consultation'));
        $this->assertSame('images/public/consultation-v1.webp', PublicMedia::path('consultation'));
    }
}

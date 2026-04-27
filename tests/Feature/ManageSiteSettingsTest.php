<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManageSiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        SiteSetting::create(['site_title' => 'Test Site']);
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole('super_admin');
    }

    public function test_page_loads_with_current_settings(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->assertSet('data.site_title', 'Test Site')
            ->assertSee('Site Settings');
    }

    public function test_can_save_text_fields(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'site_title' => 'New Title',
                'site_tagline' => 'A great tagline',
                'site_description' => 'Some meta description.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('site_settings', [
            'site_title' => 'New Title',
            'site_tagline' => 'A great tagline',
        ]);
    }

    public function test_site_title_is_required(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm(['site_title' => ''])
            ->call('save')
            ->assertHasFormErrors(['site_title' => 'required']);
    }

    public function test_can_upload_logo(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'site_title' => 'Test Site',
                'logo_path' => UploadedFile::fake()->image('logo.png'),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseMissing('site_settings', ['logo_path' => null]);
    }

    public function test_oversized_favicon_is_rejected(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'site_title' => 'Test Site',
                'favicon_path' => UploadedFile::fake()->image('fav.png')->size(129),
            ])
            ->call('save')
            ->assertHasFormErrors(['favicon_path']);
    }

    public function test_save_busts_cache(): void
    {
        Cache::put('site_setting', SiteSetting::first(), 3600);

        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm(['site_title' => 'Updated'])
            ->call('save');

        $this->assertFalse(Cache::has('site_setting'));
    }
}

<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        SiteSetting::create(['type' => 'organization', 'site_title' => ['id' => 'Test Site', 'en' => 'Test Site']]);
        SiteSetting::create(['type' => 'personal', 'site_title' => ['id' => 'Personal', 'en' => 'Personal']]);
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole('super_admin');
    }

    public function test_page_loads_with_current_settings(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->assertSet('data.site_title.id', 'Test Site')
            ->assertSee('Site Settings');
    }

    public function test_can_save_text_fields(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'site_title.id' => 'New Title',
                'site_tagline.id' => 'A great tagline',
                'site_description.id' => 'Some meta description.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $setting = SiteSetting::first();
        $this->assertEquals('New Title', $setting->getTranslation('site_title', 'id'));
        $this->assertEquals('A great tagline', $setting->getTranslation('site_tagline', 'id'));
    }

    public function test_site_title_is_required(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm(['site_title.id' => ''])
            ->call('save')
            ->assertHasFormErrors(['site_title.id' => 'required']);
    }

    public function test_can_upload_logo(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'site_title.id' => 'Test Site',
                'logo_path' => UploadedFile::fake()->image('logo.png'),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertNotNull(SiteSetting::organization()->logo_path);
    }

    public function test_oversized_favicon_is_rejected(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'site_title.id' => 'Test Site',
                'favicon_path' => UploadedFile::fake()->image('fav.png')->size(1025),
            ])
            ->call('save')
            ->assertHasFormErrors(['favicon_path']);
    }

    public function test_save_persists_to_database(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm(['site_title.id' => 'Updated Title'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertEquals('Updated Title', SiteSetting::organization()->getTranslation('site_title', 'id'));
    }

    public function test_personal_site_toggle_saves_to_org_row_only(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageSiteSettings::class)
            ->fillForm(['is_personal_site' => true])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue((bool) SiteSetting::organization()->is_personal_site);
        $this->assertFalse((bool) SiteSetting::personal()->is_personal_site);
    }
}

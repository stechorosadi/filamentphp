<?php

namespace Tests\Feature;

use App\Filament\Pages\ManagePersonalSiteSettings;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManagePersonalSiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        SiteSetting::create(['type' => 'organization', 'site_title' => ['id' => 'Org', 'en' => 'Org']]);
        SiteSetting::create(['type' => 'personal', 'site_title' => ['id' => 'Personal', 'en' => 'Personal']]);
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole('super_admin');
    }

    public function test_can_load_personal_settings_form(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManagePersonalSiteSettings::class)
            ->assertSet('data.site_title.id', 'Personal')
            ->assertSee('Personal Site Settings');
    }

    public function test_personal_settings_saves_to_personal_row_only(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManagePersonalSiteSettings::class)
            ->fillForm(['site_title.id' => 'My Name'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertEquals('My Name', SiteSetting::personal()->getTranslation('site_title', 'id'));
        $this->assertEquals('Org', SiteSetting::organization()->getTranslation('site_title', 'id'));
    }

    public function test_personal_member_select_saves_correctly(): void
    {
        $member = TeamMember::factory()->create(['is_visible' => true]);
        $this->actingAs($this->admin());

        Livewire::test(ManagePersonalSiteSettings::class)
            ->fillForm([
                'site_title.id' => 'Personal',
                'personal_member_id' => $member->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertEquals($member->id, SiteSetting::personal()->personal_member_id);
        $this->assertNull(SiteSetting::organization()->personal_member_id);
    }
}

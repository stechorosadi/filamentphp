<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PersonalSiteModeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        SiteSetting::create([
            'type' => 'organization',
            'site_title' => ['id' => 'Org Site', 'en' => 'Org Site'],
        ]);

        SiteSetting::create([
            'type' => 'personal',
            'site_title' => ['id' => 'Personal Site', 'en' => 'Personal Site'],
        ]);
    }

    public function test_personal_mode_is_off_by_default(): void
    {
        $this->assertFalse((bool) SiteSetting::organization()->is_personal_site);
    }

    public function test_homepage_shows_personal_home_view_when_personal_mode_on(): void
    {
        $member = TeamMember::factory()->create(['is_visible' => true]);

        SiteSetting::organization()->update([
            'is_personal_site' => true,
            'personal_member_id' => $member->id,
        ]);
        SiteSetting::personal()->update(['personal_member_id' => $member->id]);

        $response = $this->get('/id/');

        $response->assertOk()
            ->assertViewIs('personal-home');
    }

    public function test_personal_mode_guard_skips_when_member_id_is_null(): void
    {
        SiteSetting::organization()->update(['is_personal_site' => true]);
        // personal row's personal_member_id is null (default)

        // Guard: is_personal_site is true but personal row has no member_id → condition is false
        $this->assertTrue((bool) SiteSetting::organization()->is_personal_site);
        $this->assertNull(SiteSetting::personal()->personal_member_id);
        $this->assertFalse(
            SiteSetting::organization()->is_personal_site && SiteSetting::personal()->personal_member_id
        );
    }

    public function test_personal_home_contains_member_name(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $member = TeamMember::factory()->create(['is_visible' => true, 'user_id' => $user->id]);

        SiteSetting::organization()->update([
            'is_personal_site' => true,
            'personal_member_id' => $member->id,
        ]);
        SiteSetting::personal()->update(['personal_member_id' => $member->id]);

        $response = $this->get('/id/');

        $response->assertOk()
            ->assertSee('John Doe');
    }

    public function test_personal_home_shows_only_published_non_archived_blogs(): void
    {
        $user = User::factory()->create();
        $member = TeamMember::factory()->create(['is_visible' => true, 'user_id' => $user->id]);

        $published = Content::create([
            'user_id' => $user->id,
            'slug' => 'published-post',
            'title' => ['id' => 'Published Post', 'en' => 'Published Post'],
            'content' => ['id' => 'body', 'en' => 'body'],
            'published' => true,
            'archived' => false,
        ]);
        $unpublished = Content::create([
            'user_id' => $user->id,
            'slug' => 'draft-post',
            'title' => ['id' => 'Draft Post', 'en' => 'Draft Post'],
            'content' => ['id' => 'body', 'en' => 'body'],
            'published' => false,
            'archived' => false,
        ]);
        $archived = Content::create([
            'user_id' => $user->id,
            'slug' => 'archived-post',
            'title' => ['id' => 'Archived Post', 'en' => 'Archived Post'],
            'content' => ['id' => 'body', 'en' => 'body'],
            'published' => true,
            'archived' => true,
        ]);

        SiteSetting::organization()->update([
            'is_personal_site' => true,
            'personal_member_id' => $member->id,
        ]);
        SiteSetting::personal()->update(['personal_member_id' => $member->id]);

        $response = $this->get('/id/');

        $response->assertOk()
            ->assertViewHas('blogs', fn ($blogs) => $blogs->contains($published)
                && ! $blogs->contains($unpublished)
                && ! $blogs->contains($archived));
    }
}

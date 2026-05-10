<?php

namespace Tests\Feature\Team;

use App\Models\Content;
use App\Models\ContentClassification;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamMemberProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_profile_accessible_by_nickname(): void
    {
        $member = TeamMember::factory()->create(['nickname' => 'john-doe']);

        $response = $this->get('/id/team/john-doe');

        $response->assertOk();
        $response->assertSee($member->fullName());
    }

    public function test_member_profile_not_accessible_without_nickname(): void
    {
        $member = TeamMember::factory()->withoutNickname()->create();

        $response = $this->get('/id/team/'.$member->id);

        $response->assertNotFound();
    }

    public function test_hidden_member_returns_404(): void
    {
        TeamMember::factory()->hidden()->create(['nickname' => 'hidden-member']);

        $response = $this->get('/id/team/hidden-member');

        $response->assertNotFound();
    }

    public function test_card_tabs_section_shown_when_user_linked(): void
    {
        $user = User::factory()->create();
        TeamMember::factory()->create([
            'nickname' => 'linked-member',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/id/team/linked-member');

        $response->assertOk();
        $response->assertSee('activeTab');
    }

    public function test_blog_content_loads_for_member_with_linked_user(): void
    {
        $blogClassification = ContentClassification::factory()->create([
            'name' => ['id' => 'Blog', 'en' => 'Blog'],
            'slug' => 'blog',
        ]);

        $user = User::factory()->create();
        TeamMember::factory()->create([
            'nickname' => 'author-member',
            'user_id' => $user->id,
        ]);

        $post = Content::create([
            'user_id' => $user->id,
            'content_classification_id' => $blogClassification->id,
            'title' => ['id' => 'Test Article', 'en' => 'Test Article'],
            'slug' => 'test-article',
            'excerpt' => ['id' => 'Excerpt here', 'en' => ''],
            'content' => ['id' => 'Body content', 'en' => ''],
            'published' => true,
            'archived' => false,
            'article_date' => now()->toDateString(),
        ]);

        $response = $this->get('/id/team/author-member');

        $response->assertOk();
        $response->assertViewHas('blogs', fn ($blogs) => $blogs->contains($post));
    }

    public function test_non_blog_classified_content_not_included_in_blog(): void
    {
        ContentClassification::factory()->create([
            'name' => ['id' => 'Blog', 'en' => 'Blog'],
            'slug' => 'blog',
        ]);
        $other = ContentClassification::factory()->create([
            'name' => ['id' => 'Artikel', 'en' => 'Article'],
            'slug' => 'artikel',
        ]);

        $user = User::factory()->create();
        TeamMember::factory()->create([
            'nickname' => 'author-member-3',
            'user_id' => $user->id,
        ]);

        Content::create([
            'user_id' => $user->id,
            'content_classification_id' => $other->id,
            'title' => ['id' => 'Non-blog Article', 'en' => ''],
            'slug' => 'non-blog-article',
            'excerpt' => ['id' => '', 'en' => ''],
            'content' => ['id' => '', 'en' => ''],
            'published' => true,
            'archived' => false,
            'article_date' => now()->toDateString(),
        ]);

        $response = $this->get('/id/team/author-member-3');

        $response->assertOk();
        $response->assertViewHas('blogs', fn ($blogs) => $blogs->isEmpty());
    }

    public function test_unpublished_content_not_included_in_blog(): void
    {
        $blogClassification = ContentClassification::factory()->create([
            'name' => ['id' => 'Blog', 'en' => 'Blog'],
            'slug' => 'blog',
        ]);

        $user = User::factory()->create();
        TeamMember::factory()->create([
            'nickname' => 'author-member-2',
            'user_id' => $user->id,
        ]);

        Content::create([
            'user_id' => $user->id,
            'content_classification_id' => $blogClassification->id,
            'title' => ['id' => 'Draft Article', 'en' => ''],
            'slug' => 'draft-article',
            'excerpt' => ['id' => '', 'en' => ''],
            'content' => ['id' => '', 'en' => ''],
            'published' => false,
            'archived' => false,
            'article_date' => now()->toDateString(),
        ]);

        $response = $this->get('/id/team/author-member-2');

        $response->assertOk();
        $response->assertViewHas('blogs', fn ($blogs) => $blogs->isEmpty());
    }
}

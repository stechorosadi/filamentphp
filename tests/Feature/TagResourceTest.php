<?php

namespace Tests\Feature;

use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Models\Tag;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TagResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole('super_admin');
    }

    public function test_can_list_tags(): void
    {
        $this->actingAs($this->admin());
        $tags = Tag::factory()->count(3)->create();

        Livewire::test(ListTags::class)
            ->assertCanSeeTableRecords($tags);
    }

    public function test_can_create_tag(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateTag::class)
            ->fillForm(['name' => 'Laravel'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Tag::class, [
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);
    }

    public function test_slug_is_auto_generated(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateTag::class)
            ->fillForm(['name' => 'Web Development'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Tag::class, ['slug' => 'web-development']);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateTag::class)
            ->fillForm(['name' => null])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    }

    public function test_slug_must_be_unique(): void
    {
        $this->actingAs($this->admin());
        Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);

        Livewire::test(CreateTag::class)
            ->fillForm(['name' => 'Laravel'])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }

    public function test_can_edit_tag(): void
    {
        $this->actingAs($this->admin());
        $tag = Tag::factory()->create(['name' => 'Old Name', 'slug' => 'old-name']);

        Livewire::test(EditTag::class, ['record' => $tag->id])
            ->fillForm(['name' => 'New Name'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Tag::class, [
            'id' => $tag->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_delete_tag(): void
    {
        $this->actingAs($this->admin());
        $tag = Tag::factory()->create();

        Livewire::test(EditTag::class, ['record' => $tag->id])
            ->callAction(DeleteAction::class)
            ->assertRedirect();

        $this->assertModelMissing($tag);
    }
}

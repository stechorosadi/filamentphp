<?php

namespace Tests\Feature;

use App\Filament\Resources\ContentClassificationResource\Pages\CreateContentClassification;
use App\Filament\Resources\ContentClassificationResource\Pages\EditContentClassification;
use App\Filament\Resources\ContentClassificationResource\Pages\ListContentClassifications;
use App\Models\ContentClassification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ContentClassificationResourceTest extends TestCase
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

    public function test_can_list_classifications(): void
    {
        $this->actingAs($this->admin());
        $classifications = ContentClassification::factory()->count(3)->create();

        Livewire::test(ListContentClassifications::class)
            ->assertCanSeeTableRecords($classifications);
    }

    public function test_can_create_classification_with_name_only(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateContentClassification::class)
            ->fillForm(['name' => 'Article'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(ContentClassification::class, ['name' => 'Article', 'slug' => 'article']);
    }

    public function test_can_create_classification_with_icon(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateContentClassification::class)
            ->fillForm([
                'name' => 'Blog',
                'icon' => 'heroicon-o-pencil-square',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(ContentClassification::class, [
            'name' => 'Blog',
            'icon' => 'heroicon-o-pencil-square',
        ]);
    }

    public function test_can_create_classification_with_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(CreateContentClassification::class)
            ->fillForm([
                'name' => 'Announcement',
                'image' => UploadedFile::fake()->image('announcement.png', 100, 100),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(ContentClassification::class, ['name' => 'Announcement']);
    }

    public function test_image_rejects_non_png(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(CreateContentClassification::class)
            ->fillForm([
                'name' => 'Opinion',
                'image' => UploadedFile::fake()->image('opinion.jpg', 100, 100),
            ])
            ->call('create')
            ->assertHasFormErrors(['image']);
    }

    public function test_image_rejects_file_over_1mb(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(CreateContentClassification::class)
            ->fillForm([
                'name' => 'Opinion',
                'image' => UploadedFile::fake()->image('opinion.png')->size(1025),
            ])
            ->call('create')
            ->assertHasFormErrors(['image']);
    }

    public function test_can_edit_classification_icon_and_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        $classification = ContentClassification::factory()->create();

        Livewire::test(EditContentClassification::class, ['record' => $classification->id])
            ->fillForm([
                'icon' => 'heroicon-o-tag',
                'image' => UploadedFile::fake()->image('tag.png', 100, 100),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(ContentClassification::class, [
            'id' => $classification->id,
            'icon' => 'heroicon-o-tag',
        ]);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateContentClassification::class)
            ->fillForm(['name' => null])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    }
}

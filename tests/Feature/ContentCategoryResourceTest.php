<?php

namespace Tests\Feature;

use App\Filament\Resources\ContentCategoryResource\Pages\CreateContentCategory;
use App\Filament\Resources\ContentCategoryResource\Pages\EditContentCategory;
use App\Filament\Resources\ContentCategoryResource\Pages\ListContentCategories;
use App\Models\ContentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ContentCategoryResourceTest extends TestCase
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

    public function test_can_list_categories(): void
    {
        $this->actingAs($this->admin());
        $categories = ContentCategory::factory()->count(3)->create();

        Livewire::test(ListContentCategories::class)
            ->assertCanSeeTableRecords($categories);
    }

    public function test_can_create_category_with_name_only(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateContentCategory::class)
            ->fillForm(['name' => 'Technology'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(ContentCategory::class, ['name' => 'Technology', 'slug' => 'technology']);
    }

    public function test_can_create_category_with_icon(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateContentCategory::class)
            ->fillForm([
                'name' => 'Science',
                'icon' => 'heroicon-o-beaker',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(ContentCategory::class, [
            'name' => 'Science',
            'icon' => 'heroicon-o-beaker',
        ]);
    }

    public function test_can_create_category_with_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(CreateContentCategory::class)
            ->fillForm([
                'name' => 'Finance',
                'image' => UploadedFile::fake()->image('finance.png', 100, 100),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(ContentCategory::class, ['name' => 'Finance']);
    }

    public function test_image_rejects_non_png(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(CreateContentCategory::class)
            ->fillForm([
                'name' => 'Arts',
                'image' => UploadedFile::fake()->image('arts.jpg', 100, 100),
            ])
            ->call('create')
            ->assertHasFormErrors(['image']);
    }

    public function test_image_rejects_file_over_1mb(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(CreateContentCategory::class)
            ->fillForm([
                'name' => 'Arts',
                'image' => UploadedFile::fake()->image('arts.png')->size(1025),
            ])
            ->call('create')
            ->assertHasFormErrors(['image']);
    }

    public function test_can_edit_category_icon_and_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        $category = ContentCategory::factory()->create();

        Livewire::test(EditContentCategory::class, ['record' => $category->id])
            ->fillForm([
                'icon' => 'heroicon-o-academic-cap',
                'image' => UploadedFile::fake()->image('cat.png', 100, 100),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(ContentCategory::class, [
            'id' => $category->id,
            'icon' => 'heroicon-o-academic-cap',
        ]);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateContentCategory::class)
            ->fillForm(['name' => null])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    }
}

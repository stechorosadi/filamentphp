<?php

namespace Tests\Feature\Services;

use App\Services\ImageConverter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ImageConverterTest extends TestCase
{
    private function fakePng(): string
    {
        $path = sys_get_temp_dir().'/'.Str::uuid().'.png';
        $img = imagecreatetruecolor(80, 80);
        imagepng($img, $path);
        imagedestroy($img);

        return $path;
    }

    private function fakeJpeg(): string
    {
        $path = sys_get_temp_dir().'/'.Str::uuid().'.jpg';
        $img = imagecreatetruecolor(80, 80);
        imagejpeg($img, $path, 80);
        imagedestroy($img);

        return $path;
    }

    public function test_converts_jpeg_to_webp(): void
    {
        Storage::fake('public');

        $path = ImageConverter::encodeToWebp($this->fakeJpeg(), 'team');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_converts_png_to_webp(): void
    {
        Storage::fake('public');

        $path = ImageConverter::encodeToWebp($this->fakePng(), 'team');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_stores_in_correct_directory(): void
    {
        Storage::fake('public');

        $path = ImageConverter::encodeToWebp($this->fakeJpeg(), 'avatars');

        $this->assertNotNull($path);
        $this->assertStringStartsWith('avatars/', $path);
    }

    public function test_preserves_transparent_png(): void
    {
        Storage::fake('public');

        // Create a PNG with a fully transparent background
        $path = sys_get_temp_dir().'/'.Str::uuid().'.png';
        $img = imagecreatetruecolor(80, 80);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);
        imagepng($img, $path);
        imagedestroy($img);

        $result = ImageConverter::encodeToWebp($path, 'site');

        $this->assertNotNull($result);
        $this->assertStringEndsWith('.webp', $result);
        Storage::disk('public')->assertExists($result);
    }

    public function test_returns_null_for_invalid_source(): void
    {
        Storage::fake('public');

        $path = ImageConverter::encodeToWebp('/non-existent-file.jpg', 'team');

        $this->assertNull($path);
    }
}

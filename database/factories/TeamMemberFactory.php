<?php

namespace Database\Factories;

use App\Enums\TeamMemberStatus;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'nickname' => Str::slug($name),
            'name' => $name,
            'position' => ['id' => fake()->jobTitle(), 'en' => ''],
            'front_title' => ['id' => '', 'en' => ''],
            'back_title' => ['id' => '', 'en' => ''],
            'sort_order' => 0,
            'is_visible' => true,
            'status' => TeamMemberStatus::Active,
        ];
    }

    public function hidden(): static
    {
        return $this->state(['is_visible' => false]);
    }

    public function withoutNickname(): static
    {
        return $this->state(['nickname' => null]);
    }
}

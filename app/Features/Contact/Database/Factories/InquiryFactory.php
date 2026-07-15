<?php

declare(strict_types=1);

namespace App\Features\Contact\Database\Factories;

use App\Features\Contact\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
final class InquiryFactory extends Factory
{
    /**
     * @var class-string<Inquiry>
     */
    protected $model = Inquiry::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company' => fake()->company(),
            'contact_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'current_task' => 'Excelで在庫を管理している',
            'improvement' => '入力を自動化したい',
            'chat_transcript' => null,
            'status' => 'new',
        ];
    }
}

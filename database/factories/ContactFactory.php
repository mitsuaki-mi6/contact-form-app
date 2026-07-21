<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'gender' => fake()->randomElement([1, 2, 3]), // 1:男性, 2:女性, 3:その他
            'email' => fake()->safeEmail,
            'tel' => fake()->regexify('[0-9]{10,11}'), // 10〜11桁の数字（ハイフンなし）
            'address' => fake()->address,
            'building' => fake()->randomElement([fake()->secondaryAddress, null]), // 半分はNULLにする
            'detail' => fake()->realText(100), // 120文字以内のテキスト
        ];
    }
}

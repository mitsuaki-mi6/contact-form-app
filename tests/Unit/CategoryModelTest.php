<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function カテゴリから紐づく複数のお問い合わせ（has_many）が正しく取得できる(): void
    {
        // Arrange
        // カテゴリを1つ作成し、それに紐づくお問い合わせを3つ作成する
        $category = Category::factory()->create();
        $contacts = Contact::factory()->count(3)->create([
            'category_id' => $category->id,
        ]);

        // Act
        // カテゴリモデルのリレーションを通じてお問い合わせを取得する
        $results = $category->contacts;

        // Assert
        // 取得した数が3つであり、かつIDが一致していることを確認する
        $this->assertCount(3, $results);
        foreach ($contacts as $index => $contact) {
            $this->assertEquals($contact->id, $results[$index]->id);
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function お問い合わせフォーム入力ページが正常に表示される(): void
    {
        // Act
        $response = $this->get('/');
        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function categoriesとtagsがビュー変数として渡される(): void
    {
        // Arrange
        $category = Category::factory()->create(['content' => 'テストカテゴリ']);
        $tag = Tag::factory()->create(['name' => 'テストタグ']);
        // Act
        $response = $this->get('/');
        // Assert
        $response->assertViewHas('categories', function ($categories) use ($category) {
            return $categories->contains($category);
        });
        $response->assertViewHas('tags', function ($tags) use ($tag) {
            return $tags->contains($tag);
        });
    }

    /** @test */
    public function カテゴリ名とタグ名がページに表示される(): void
    {
        // Arrange
        Category::factory()->create(['content' => 'テストカテゴリ']);
        Tag::factory()->create(['name' => 'テストタグ']);
        // Act
        $response = $this->get('/');
        // Assert
        $response->assertSee('テストカテゴリ');
        $response->assertSee('テストタグ');
    }

    /** @test */
    public function サンクスページが正常に表示される(): void
    {
        // Act
        $response = $this->get('/thanks');
        // Assert
        $response->assertStatus(200);
    }
}

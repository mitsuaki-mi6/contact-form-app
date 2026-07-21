<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;

class TagFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function タグ編集画面表示される(): void
    {
        // Arrange
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => 'テストタグ']);

        // Act
        $response = $this->actingAs($user)->get("/admin/tags/{$tag->id}/edit");

        // Assert
        $response->assertStatus(200);
        $response->assertSee('テストタグ');
    }

    /** @test */
    public function タグが作成される(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->post('/admin/tags', [
            'name' => '新規タグ',
        ]);

        // Assert
        $response->assertStatus(302);
        $this->assertDatabaseHas('tags', ['name' => '新規タグ']);
    }

    /** @test */
    public function タグが更新される(): void
    {
        // Arrange
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => 'テストタグ']);

        // Act
        $response = $this->actingAs($user)->put("/admin/tags/{$tag->id}", [
            'name' => '更新タグ',
        ]);

        // Assert
        $response->assertStatus(302);
        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => '更新タグ']);
    }

    /** @test */
    public function タグが削除される(): void
    {
        // Arrange
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => 'テストタグ']);

        // Act
        $response = $this->actingAs($user)->delete("/admin/tags/{$tag->id}");

        // Assert
        $response->assertStatus(302);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    /** @test */
    public function 未認証ユーザーはリダイレクトされる(): void
    {
        // Arrange
        $tag = Tag::factory()->create(['name' => 'テストタグ']);

        // Act
        $response = $this->get("/admin/tags/{$tag->id}/edit");

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}

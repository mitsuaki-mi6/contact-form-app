<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function お問い合わせがカテゴリ情報付きで表示される(): void
    {
        // Arrange
        // 管理者ユーザーを作成してログイン状態にする
        $user = User::factory()->create();
        // カテゴリを作成
        $category = Category::factory()->create(['content' => 'テストカテゴリ']);
        // お問い合わせを作成
        $contact = Contact::factory()->create([
            'category_id' => $category->id,
            'first_name' => '山田',
            'last_name' => '太郎',
        ]);

        // Act
        $response = $this->actingAs($user)->get('admin/contacts/'.$contact->id);

        // Assert
        $response->assertStatus(200);
        $response->assertSee('山田');
        $response->assertSee('テストカテゴリ');
    }
}

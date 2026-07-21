<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSubmitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function バリデーションエラー時はリダイレクトされエラーが返る(): void
    {
        // Arrange: フォームから送られてくるはずのデータを準備
        $category = Category::factory()->create(['content' => 'テストカテゴリ']);
        // テストデータを投入
        $inputData = [
            'first_name' => '', // 必須項目を空にする
            'last_name' => '', // 必須項目を空にする
            'email' => 'invalid-email', // メール形式じゃない値にする
        ];

        // Act: POSTで送信
        $response = $this->post('/contacts/confirm', $inputData);

        // Assert:
        // ステータスコード302（リダイレクト）
        $response->assertStatus(302);
        // セッションにバリデーションエラーが含まれていること
        $response->assertSessionHasErrors(['first_name', 'last_name', 'email']);
    }

    /** @test */
    public function 送信時テーブルにレコードが保存されリダイレクト(): void
    {
        // Arrange
        // フォームから送られてくるはずのデータを準備
        $category = Category::factory()->create(['content' => 'テストカテゴリ']);
        // テストデータを投入
        $inputData = [
            'first_name' => 'テスト',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'category_id' => $category->id,
            'detail' => 'お問い合わせ内容です',
        ];

        // Act: POSTでcontactsにアクセス
        $response = $this->post('/contacts', $inputData);

        // Assert
        // データベースにレコードが保存されているか
        $this->assertDatabaseHas('contacts', [
            'first_name' => 'テスト',
            'email' => 'test@example.com',
            'detail' => 'お問い合わせ内容です',
        ]);

        // 完了画面へリダイレクト（302）
        $response->assertStatus(302);
        $response->assertRedirect('/thanks');
    }
}

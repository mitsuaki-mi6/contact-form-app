<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function キーワード・性別・カテゴリ・日付フィルタが機能し結果が表示される(): void
    {
        // Arrange
        // 管理者ユーザーと、検索ヒットする/しないテストデータを用意
        $user = User::factory()->create();
        $category1 = Category::factory()->create(['content' => '商品について']);
        $category2 = Category::factory()->create(['content' => '採用について']);

        // 検索条件に合致するデータ
        $targetContact = Contact::factory()->create([
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1, // 男性
            'category_id' => $category1->id,
            'created_at' => '2026-01-15 10:00:00',
        ]);

        // 検索条件に合致しないデータ（別の日付・カテゴリなど）
        Contact::factory()->create([
            'first_name' => '佐藤',
            'last_name' => '花子',
            'gender' => 2, // 女性
            'category_id' => $category2->id,
            'created_at' => '2026-02-20 10:00:00',
        ]);

        // Act
        // 検索条件でアクセス
        $response = $this->actingAs($user)->get('/admin?keyword=山田&gender=1&category_id='.$category1->id.'&date=2026-01-15');

        // Assert
        // ヒットデータが表示され
        $response->assertStatus(200);
        $response->assertSee('山田');
        // ヒットデータなし
        $response->assertDontSee('佐藤');
    }

    /** @test */
    public function 結果の表示は7件ごとにページネーションされる(): void
    {
        // Arrange
        // 8件のデータを作成
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Contact::factory()->count(8)->create([
            'category_id' => $category->id,
        ]);

        // Act
        $response = $this->actingAs($user)->get('/admin');

        // Assert
        // 1ページ目にはデータが表示され、8件目が2ページ目以降を確認
        $response->assertStatus(200);
        $response->assertViewHas('contacts', function ($contacts) {
            return $contacts->count() === 7; // 1ページあたりの表示件数が7件であること
        });
        $response->assertSee('Next'); // Nextリンクを確認(8件目が2ページ目にあることを示す)
    }

    /** @test */
    public function 表示件数がゼロ件(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        // ヒットしない(ゼロ件)キーワードで検索
        $response = $this->actingAs($user)->get('/admin?keyword=存在しない架空の文字列99999');

        // Assert: ステータス200でデータが0件
        $response->assertStatus(200);
        $response->assertSee('データがありません'); // ゼロ件時のメッセージ
    }
}

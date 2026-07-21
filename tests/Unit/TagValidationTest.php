<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Requests\StoreTagRequest;

class TagValidationTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function タグ名に入力がない(): void
    {
        // バリデーションルールの取得
        $allRules = (new StoreTagRequest())->rules();
        $rules = ['name' => $allRules['name']]; // タグ名のバリデーションルールのみを使用

        // Arrange データセット(異常系 タグ名に入力がない)
        $data = [
            'name' => '', // タグ名に入力がない
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーションが失敗することを確認
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function タグ名の文字数オーパー(): void
    {
        // Arrange
        // バリデーションルールの取得
        $allRules = (new StoreTagRequest())->rules();
        $rules = ['name' => $allRules['name']]; // タグ名のバリデーションルールのみを使用

        // Arrange データセット(異常系 タグ名の文字数オーパー)
        $data = [
            'name' => str_repeat('a', 51), // タグ名の文字数が50文字を超える
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーションが失敗することを確認
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function タグ名が重複(): void
    {
        // Arrange
        // タグを作成しておく（重複チェックのため）
        \App\Models\Tag::factory()->create(['name' => 'テストタグ']);

        // バリデーションルールの取得
        $allRules = (new StoreTagRequest())->rules();
        $rules = ['name' => $allRules['name']]; // タグ名のバリデーションルールのみを使用

        // Arrange データセット(異常系 タグ名が重複している)
        $data = [
            'name' => 'テストタグ', // 既存のタグ名
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーションが失敗することを確認
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function タグ名の入力、文字数制限ない、一意性が維持されている(): void
    {
        // Arrange
        // バリデーションルールの取得
        $allRules = (new StoreTagRequest())->rules();
        $rules = ['name' => $allRules['name']]; // タグ名のバリデーションルールのみを使用

        // Arrange データセット(正常系 タグ名が適切に入力されている)
        $data = [
            'name' => '新しいタグ', // 既存のタグ名
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        /// Assert バリデーション通過を確認
        $this->assertFalse($validator->fails());
    }
}

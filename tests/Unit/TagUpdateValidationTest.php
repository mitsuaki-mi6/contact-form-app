<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;

class TagUpdateValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_他で既に使用されているタグ名への変更は拒否(): void
    {
        // Arrange
        // タグを作成しておく（更新重複チェックのため）
        $existingTag = Tag::factory()->create(['name' => '重複タグ']); // すでにある名前
        $targetTag = Tag::factory()->create(['name' => '更新タグ']);   // 更新対象

        // バリデーションルールの取得
        $allRules = (new UpdateTagRequest())->rules();
        $rules = ['name' => $allRules['name']]; // タグ名のバリデーションルールのみを使用

        // Arrange データセット(異常系 タグ名が重複している)
        $data = [
            'name' => '重複タグ', // 既存のタグ名
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーションが失敗することを確認
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    public function test_自身の名前維持は可能(): void
    {
        // Arrange
        // タグを作成しておく（自身更新のため）
        Tag::factory()->create(['name' => '重複タグ']);
        $targetTag = Tag::factory()->create(['name' => '更新タグ']);

        $request = UpdateTagRequest::create('/admin/tags/' . $targetTag->id, 'PUT');
        $route = new \Illuminate\Routing\Route('PUT', 'admin/tags/{id}', []);
        $route->bind($request);
        $route->parameters(['id' => $targetTag->id]);
        $request->setRouteResolver(fn() => $route);

        // バリデーションルールの取得
        $rules = ['name' => $request->rules()['name']];

        // Arrange データセット(正常系 自身の名前を維持する)
        $data = [
            'name' => '更新タグ', // 自身の名前を維持する
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        /// Assert バリデーション通過を確認
        $this->assertFalse($validator->fails());
    }

    public function test_重複しない名前の変更は可能(): void
    {
        // Arrange
        // タグを作成しておく
        $existingTag = Tag::factory()->create(['name' => '重複タグ']); // すでにある名前
        $targetTag = Tag::factory()->create(['name' => '更新タグ']);   // 更新対象

        // バリデーションルールの取得
        $allRules = (new UpdateTagRequest())->rules();
        $rules = ['name' => $allRules['name']]; // タグ名のバリデーションルールのみを使用

        // Arrange データセット(正常系 重複しない名前の変更)
        $data = [
            'name' => '新規タグ', // 重複しない新しい名前
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        /// Assert バリデーション通過を確認
        $this->assertFalse($validator->fails());
    }
}

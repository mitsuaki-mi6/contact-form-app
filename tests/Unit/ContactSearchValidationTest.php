<?php

namespace Tests\Unit;

use App\Http\Requests\IndexContactRequest;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSearchValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 不正な性別値を拒否する(): void
    {
        // バリデーションルールの取得
        $allRules = (new IndexContactRequest)->rules();
        $rules = ['gender' => $allRules['gender']]; // 性別のバリデーションルールのみを使用

        // Arrange データセット(異常系 不正な性別値)
        $data = [
            'gender' => '9', // 不正な性別値
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーションが失敗することを確認
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('gender', $validator->errors()->toArray());

    }

    /** @test */
    public function 全て項目を受け付ける(): void
    {
        // Arrange
        // カテゴリを作成
        Category::factory()->create(['id' => 1]);

        // バリデーションルールの取得
        $rules = (new IndexContactRequest)->rules();

        // データセット(正常系 全ての項目が入力されている)
        $data = [
            'keyword' => 'ジョン',
            'gender' => 1,
            'category_id' => 1,
            'date' => '2026-01-03',
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーション通過を確認
        $this->assertFalse($validator->fails());
    }
}

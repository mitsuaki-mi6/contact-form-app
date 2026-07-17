<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Requests\ContactRequest;

class ContactValidationTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function 不正な電話番号形式は拒否する(): void
    {
        // バリデーションルールの取得
        $allRules = (new ContactRequest())->rules();
        $rules = ['tel' => $allRules['tel']]; // 電話番号のバリデーションルールのみを使用

        // Arrange データセット(異常系 不正な電話番号形式)
        $data = [
            'tel' => '11111', // 不正な電話番号形式
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーションが失敗することを確認
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('tel', $validator->errors()->toArray());
    }

    /** @test */
    public function 必須項目に漏れがある(): void
    {
        // バリデーションルールの取得
        $allRules = (new ContactRequest())->rules();
        $rules = ['first_name' => $allRules['first_name']]; // 名前のバリデーションルールのみを使用

        // Arrange データセット(異常系 必須項目に漏れがある)
        $data = [
            'first_name' => '', // 必須項目に漏れがある
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーションが失敗することを確認
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('first_name', $validator->errors()->toArray());
    }

    /** @test */
    public function 全ての必須項目を受け付ける(): void
    {
        // Arrange
        // カテゴリを作成
        \App\Models\Category::factory()->create(['id' => 1]);

        // バリデーションルールの取得
        $rules = (new ContactRequest())->rules();

        // データセット(正常系 全ての必須項目が入力されている)
        $data = [
            'first_name' => 'ジョン',
            'last_name' => 'ディオ',
            'gender' => 1,
            'email' => 'john.doe@example.com',
            'tel' => '09012345678',
            'address' => 'フランスのマルセイユ',
            'category_id' => 1,
            'detail' => '問い合わせのテストです。',
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーション通過を確認
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function タグ入力を受け付ける(): void
    {
        // Arrange
        // カテゴリとタグを準備作成
        \App\Models\Category::factory()->create(['id' => 1]);
        \App\Models\Tag::factory()->create(['id' => 1]);
        \App\Models\Tag::factory()->create(['id' => 2]);
        \App\Models\Tag::factory()->create(['id' => 3]);

        // バリデーションルールの取得
        $rules = (new ContactRequest())->rules();

        // データセット(正常系 タグ入力に入力がある)
        $data = [
            'first_name' => 'ジョン',
            'last_name' => 'ディオ',
            'gender' => 1,
            'email' => 'john.doe@example.com',
            'tel' => '09012345678',
            'address' => 'フランスのマルセイユ',
            'category_id' => 1,
            'detail' => '問い合わせのテストです。',
            'tag_ids' => [1, 2, 3], // タグ入力
        ];
        // Act バリデーションの実行
        $validator = \Validator::make($data, $rules);

        // Assert バリデーション通過を確認
        $this->assertFalse($validator->fails());
    }
}

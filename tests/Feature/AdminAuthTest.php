<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 未認証ユーザーはloginにリダイレクト(): void
    {
        // Act: 管理画面のURLにアクセス
        $response = $this->get('/admin');

        // Assert: ログイン画面へリダイレクトされること
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function 認証されたユーザーのみが管理ダッシュボードを表示(): void
    {
        // Arrange: 管理者ユーザーを作成してログイン状態にする
        $user = User::factory()->create();

        // Act: 管理画面のURLにアクセス
        $response = $this->actingAs($user)->get('/admin');

        // Assert: 画面が表示される（200）
        $response->assertStatus(200);
    }
}

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

// ==========================================
// 1. 公開ページ
// ==========================================
// PG01	お問い合わせフォーム入力ページ
Route::get('/', [ContactController::class, 'index'])->name('contact.index');

// PG02	お問い合わせフォーム確認ページ
Route::post('/contacts/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');

// PG02	お問い合わせフォーム送信処理(テーブル更新)
Route::post('/contacts', [ContactController::class, 'store'])->name('contact.store');

//PG03	サンクスページ
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');

// ==========================================
// 2. 認証ページ
// ==========================================
// 認証が必要なルート
Route::middleware('auth')->group(function () {
    // PG05 管理画面
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TagController;

// ==========================================
// 1. お問い合わせフォーム(公開ページ)
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
// 2. 管理画面(認証ページ)
// ==========================================
// 認証が必要なルート
Route::middleware('auth')->group(function () {
    // --- 管理画面系 (AdminController) ---
    // PG05 一覧表示
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // PG05 詳細表示
    Route::get('/admin/contacts/{id}', [AdminController::class, 'show'])->name('admin.show');

    // PG05 削除処理
    Route::delete('/admin/contacts/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

    // --- タグ管理系 (TagController) ---
    // PG05-3 タグ作成
    Route::post('/admin/tags', [TagController::class, 'store'])->name('admin.tags.store');

    // PG05-3 タグ編集画面表示
    Route::get('/admin/tags/{id}/edit', [TagController::class, 'edit'])->name('admin.tags.edit');

    // PG05-3 タグ更新処理
    Route::put('/admin/tags/{id}', [TagController::class, 'update'])->name('admin.tags.update');

    // PG05-3 タグ削除
    Route::delete('/admin/tags/{id}', [TagController::class, 'destroy'])->name('admin.tags.destroy');
});

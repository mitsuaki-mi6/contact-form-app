<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class AdminController extends Controller
{
    /**
     * PG05 管理画面の一覧表示
     */
    public function index(IndexContactRequest $request)
    {
        // バリデーションは IndexContactRequest で実行済み
        $request->validated();

        $query = Contact::with(['category', 'tags']);

        // キーワード検索（姓、名、メールアドレスの部分一致）
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', '%'.$keyword.'%')
                    ->orWhere('last_name', 'like', '%'.$keyword.'%')
                    ->orWhere('email', 'like', '%'.$keyword.'%');
            });
        }

        // 性別検索
        if ($request->filled('gender') && $request->input('gender') != 0) {
            $query->where('gender', $request->input('gender'));
        }

        // カテゴリ検索
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // 日付検索
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        // 7件ずつ取得（ページネーションに検索条件を引き継ぐ）
        $contacts = $query->paginate(7)->appends($request->only(['keyword', 'gender', 'category_id', 'date']));

        /**
         * PG05-3   タグ編集ページ
         */
        // タグ管理を表示用の一覧を取得
        $categories = Category::all();
        $tags = Tag::all();

        // ビューにデータを渡して表示
        return view('admin.index', compact('contacts', 'categories', 'tags'));
    }

    /**
     * PG05-2   お問い合わせ詳細ページ
     */
    public function show($id)
    {
        $contact = Contact::with(['category', 'tags'])->findOrFail($id);

        return view('admin.show', compact('contact'));
    }

    // 削除処理
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.index')->with('success', 'お問い合わせを削除しました');
    }
}

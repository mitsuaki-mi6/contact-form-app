<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\IndexContactRequest;
use App\Models\Contact;
use App\Models\Category;

class AdminController extends Controller

{
    /**
     * PG05 管理画面の一覧表示
     */
    public function index(IndexContactRequest $request)
    {
        $query = Contact::with(['category', 'tags']);

        // キーワード検索（姓、名、メールアドレスの部分一致）
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', '%' . $keyword . '%')
                    ->orWhere('last_name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
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
        // 7件ずつ取得
        $contacts = $query->paginate(7)->appends($request->only(['keyword', 'gender', 'category_id', 'date']));
        // 検索フォームで使うためのカテゴリ一覧を取得
        $categories = Category::all();

        // ビューにデータを渡して表示
        return view('admin.index', compact('contacts', 'categories'));
    }

    /**
     * PG05-2	お問い合わせ詳細ページ
     */
    // 詳細表示
    public function show($id)
    {
        $contact = Contact::with(['category', 'tags'])->findOrFail($id);
        return view('admin.show', compact('contact'));
    }
    // 削除処理
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.index')->with('success', 'お問い合わせを削除しました');
    }
}

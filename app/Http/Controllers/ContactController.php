<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class ContactController extends Controller
{
    /**
     * PG01 お問い合わせフォーム入力ページ
     */
    public function index()
    {
        // 画面のセレクトボックスやチェックボックスのデータを取得して渡す
        $categories = Category::all();
        $tags = Tag::all();

        return view('contact.index', compact('categories', 'tags'));
    }

    /**
     * PG02	お問い合わせフォーム確認ページ
     */
    public function confirm(ContactRequest $request)
    {
        $validated = $request->validated();
        $category = Category::find($validated['category_id']);
        $tags = isset($validated['tag_ids'])
            ? Tag::whereIn('id', $validated['tag_ids'])->get()
            : collect();

        return view('contact.confirm', compact('validated', 'category', 'tags'));
    }

    /**
     * PG02	お問い合わせフォーム入力情報の登録
     */
    public function store(ContactRequest $request)
    {
        $validated = $request->validated();
        $contact = Contact::create($validated);
        // タグが選択されていた場合の処理
        if (! empty($validated['tag_ids'])) {
            $contact->tags()->attach($validated['tag_ids']);
        }

        // 登録後、PG03　サンクスページにリダイレクト
        return redirect()->route('contact.thanks');
    }

    /**
     * PG03 サンクスページ
     */
    public function thanks()
    {
        return view('contact.thanks');
    }
}

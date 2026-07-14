<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\IndexContactRequest;
use App\Models\Contact;
use App\Models\Category;


class TagController extends Controller
{
    /**
     * PG05-3	タグ編集ページ
     */
    // タグ作成
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = new \App\Models\Tag();
        $tag->name = $request->input('name');
        $tag->save();

        return redirect()->route('admin.index')->with('success', 'タグを作成しました');
    }
    // タグ編集画面表示
    public function edit($id)
    {
        $tag = \App\Models\Tag::findOrFail($id);
        return view('admin.edit_tag', compact('tag'));
    }
    // タグ更新
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = \App\Models\Tag::findOrFail($id);
        $tag->name = $request->input('name');
        $tag->save();

        return redirect()->route('admin.index')->with('success', 'タグを更新しました');
    }
    // タグ削除
    public function destroy($id)
    {
        $tag = \App\Models\Tag::findOrFail($id);
        $tag->delete();

        return redirect()->route('admin.index')->with('success', 'タグを削除しました');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\IndexContactRequest;
use App\Models\Contact;
use App\Models\Category;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;


class TagController extends Controller
{
    /**
     * PG05-3	タグ編集ページ
     */
    // タグ作成
    public function store(StoreTagRequest $request)
    {
        $tag = new \App\Models\Tag();
        $tag->name = $request->input('name');
        $tag->save();
        return redirect()->route('admin.index')->with('success', 'タグを作成しました');
    }
    // タグ編集画面表示
    public function edit($id)
    {
        $tag = \App\Models\Tag::findOrFail($id);
        return view('admin.tags.edit', compact('tag'));
    }
    // タグ更新
    public function update(UpdateTagRequest $request, $id)
    {
        $tag = \App\Models\Tag::findOrFail($id);
        $tag->name = $request->input('name');
        $tag->save();

        return redirect()->route('admin.index')->with('success', 'タグを更新しました');
    }
    // タグ削除
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->route('admin.index')->with('success', 'タグを削除しました');
    }
}

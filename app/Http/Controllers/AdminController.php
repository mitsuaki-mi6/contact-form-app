<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller

{
    // PG05 管理画面の一覧表示（仮）
    public function index()
    {
        // 💡 ファイルを読み込まず、画面に直接メッセージを表示させる！
        return response('ログイン成功！管理画面にアクセスできました。', 200);
    }
}

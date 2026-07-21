<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        // 事前に全タグを取得（紐付けに使用するため）
        $tags = Tag::all();

        // 20件のダミーデータを1件ずつ作成して紐付ける
        for ($i = 0; $i < 20; $i++) {

            // contactsテーブルにデータを1件登録
            $contact = Contact::create([
                // 既存のcategoriesからランダムに1つのIDを取得
                'category_id' => Category::inRandomOrder()->first()->id,
                'first_name' => fake('ja_JP')->firstName,
                'last_name' => fake('ja_JP')->lastName,
                'gender' => fake()->randomElement([1, 2, 3]), // 1:男性, 2:女性, 3:その他
                'email' => fake()->safeEmail,
                'tel' => fake()->regexify('[0-9]{10,11}'), // 10〜11桁の数字（ハイフンなし）
                'address' => fake('ja_JP')->address,
                'building' => fake()->randomElement([fake('ja_JP')->secondaryAddress, null]), // 半分はNULLにする
                'detail' => fake('ja_JP')->realText(100), // 120文字以内のテキスト
            ]);

            // 既存のtagsからランダムに1〜3件を抽出して、中間テーブル（contact_tag）に紐付け
            $randomTags = $tags->random(rand(1, 3));

            $contact->tags()->attach($randomTags->pluck('id'));
        }
    }
}

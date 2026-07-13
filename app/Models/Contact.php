<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    //** 複数代入可能な属性
    protected $fillable = [
        'category_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'tel',
        'address',
        'building',
        'detail',
    ];

    //** このタスクが属するカテゴリーを取得
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //** この問い合わせに紐づくタグを取得
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    //** genderのラベルを取得
    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            1 => '男性',
            2 => '女性',
            default => 'その他',
        };
    }
}

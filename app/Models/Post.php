<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $external_id
 * @property boolean $is_active
 * @property string $title
 * @property string $description
 * @property int $user_id
 */
class Post extends Model
{
    protected $fillable = [
        'external_id',
        'is_active',
        'title',
        'description',
        'user_id'  
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeIsActive(Builder $builder)
    {
        $builder->where('is_active', '=', true);
    }

}

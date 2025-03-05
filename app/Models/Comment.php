<?php

namespace App\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @property int $external_id
 * @property boolean $is_active
 * @property string $name
 * @property string $email
 * @property int $post_id
 * @property string $description
 */
class Comment extends Model
{
    protected $fillable = [
        'external_id',
        'is_active',
        'name',
        'email',
        'post_id',
        'description'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeIsActive(Builder $builder)
    {
        $builder->where('is_active', '=', true);
    }
}

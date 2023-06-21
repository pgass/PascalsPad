<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entrie_id',
        'comment'
    ];

    //one comment belongs to one user
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    //one comment belongs to one entry
    public function entrie(): BelongsTo{
        return $this->belongsTo(Entrie::class);
    }
}

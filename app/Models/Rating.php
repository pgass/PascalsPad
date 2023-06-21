<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'user_id',
        'entrie_id',
    ];

    //one rating belongs to one user
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    //one rating belongs to one entry
    public function entrie(): BelongsTo{
        return $this->belongsTo(Entrie::class);
    }
}

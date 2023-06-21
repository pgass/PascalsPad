<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Entrie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'padlet_id',
        'title',
        'content'
    ];

    //one entry belongs to one user
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    //one entry belongs to one padlet
    public function padlet(): BelongsTo{
        return $this->belongsTo(Padlet::class);
    }

    //one entry can have many ratings
    public function ratings() : HasMany{
        return $this->hasMany(Rating::class);
    }

    //one entry can have many comments
    public function comments() : HasMany{
        return $this->hasMany(Comment::class);
    }
}

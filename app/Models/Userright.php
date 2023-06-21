<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Userright extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'padlet_id',
        'read',
        'edit',
        'delete'
    ];

    //userright belongs to one user
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}

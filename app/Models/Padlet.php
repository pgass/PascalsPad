<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Padlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_public',
        'user_id'
    ];

    //one padlet can have many entries
    public function entries() : HasMany {
        return $this->HasMany(Entrie::class);
    }

    //one padlet belongs to one user
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    //one padlet can have many userrights
    public function userrights() : HasMany{
        return $this->hasMany(Userright::class);
    }

}

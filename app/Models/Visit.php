<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 👈 IMPORTA ESSE AQUI
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // 👈 para tipagem
use App\Models\Link;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = ['link_id', 'ip_hash', 'user_agent'];

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}

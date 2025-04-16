<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_leader');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent');
    }
}

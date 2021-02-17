<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Channel extends Model
{
    use HasFactory;

    /**
     * The Subscription records for this Channel
     * @return HasMany
     */
    public function subscriptions(): HasMany {
        return $this->hasMany(Subscription::class);
    }
}

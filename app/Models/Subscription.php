<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'date',
        'count'
    ];

    protected $dates = [
        'date' => 'date:Y-m-d'
    ];

    /**
     * The Channel this Subscription record belongs to
     * @return BelongsTo
     */
    public function channel(): BelongsTo {
        return $this->belongsTo(Channel::class);
    }
}

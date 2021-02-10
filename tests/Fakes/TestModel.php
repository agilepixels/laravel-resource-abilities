<?php

namespace AgilePixels\ResourceAbilities\Tests\Fakes;

use AgilePixels\ResourceAbilities\HasAbilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestModel extends Model
{
    use HasAbilities;

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}

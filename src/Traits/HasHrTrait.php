<?php

namespace Thotam\ThotamHr\Traits;

use Thotam\ThotamHr\Models\HR;
use Thotam\ThotamHr\Models\UpdateHr;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasHrTrait {

    /**
     * Get the hr that owns the HasHrTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hr(): BelongsTo
    {
        return $this->belongsTo(HR::class, 'hr_key', 'key');
    }

    /**
     * Get the update_hr associated with the HasHrTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function update_hr(): HasOne
    {
        return $this->hasOne(UpdateHr::class, 'user_id', 'id');
    }
}

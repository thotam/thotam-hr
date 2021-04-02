<?php

namespace Thotam\ThotamHr\Traits;

use Thotam\ThotamHr\Models\HR;
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

}

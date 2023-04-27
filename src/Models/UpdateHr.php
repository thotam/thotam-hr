<?php

namespace Thotam\ThotamHr\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UpdateHr extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Userstamps;

    protected $connection = 'mysql';

    /**
     * Disable Laravel's mass assignment protection
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'update_hrs';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ngaysinh' => 'datetime',
        'ngaythuviec' => 'datetime',
    ];
}

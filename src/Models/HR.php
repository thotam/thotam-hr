<?php

namespace Thotam\ThotamHr\Models;

use App\Models\User;
use Wildside\Userstamps\Userstamps;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Thotam\ThotamHr\Traits\HasMailTrait;
use Thotam\ThotamTeam\Traits\HasNhomTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thotam\ThotamBuddy\Traits\HasBuddyTraits;
use Thotam\ThotamMkt\Traits\HasMktSubTeamTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Thotam\ThotamIcpc1hnApi\Traits\Has_iCPC1HN_Account_Trait;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class HR extends Model implements AuthorizableContract
{
    use HasFactory;
    use SoftDeletes;
    use Userstamps;
    use HasRoles;
    use Authorizable;
    use HasNhomTrait;
    use HasMailTrait;
    use HasBuddyTraits;
    use HasMktSubTeamTrait;
    use Has_iCPC1HN_Account_Trait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'hoten', 'ten', 'ngaysinh', 'ngaythuviec', 'active', 'sync',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrs';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * guard_name
     *
     * @var string
     */
    protected $guard_name = 'web';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ngaysinh' => 'datetime',
        'ngaythuviec' => 'datetime',
    ];

    /**
     * Get all of the users for the HR
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'hr_key', 'key');
    }
}

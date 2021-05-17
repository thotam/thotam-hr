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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Thotam\ThotamTeam\Models\Nhom;

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

    /**
     * Get all of the mails for the HR
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mails(): HasMany
    {
        return $this->hasMany(MailHR::class, 'hr_key', 'key');
    }

    /**
     * getIsThanhvienAttribute
     *
     * @return void
     */
    public function getIsThanhvienAttribute()
    {
        return !!count($this->thanhvien_of_nhoms);
    }

    /**
     * getIsQuanlyAttribute
     *
     * @return void
     */
    public function getIsQuanlyAttribute()
    {
        return !!count($this->quanly_of_nhoms);
    }

    /**
     * getIsMktQuanlyAttribute
     *
     * @return void
     */
    public function getIsMktQuanlyAttribute()
    {
        $mkt_teams = Nhom::where("active", true)->where("phan_loai_id", 4)->get();

        foreach ($this->quanly_of_nhoms as $nhom) {
            if ($mkt_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsMktThanhvienAttribute
     *
     * @return void
     */
    public function getIsMktThanhvienAttribute()
    {
        $mkt_teams = Nhom::where("active", true)->where("phan_loai_id", 4)->get();

        foreach ($this->thanhvien_of_nhoms as $nhom) {
            if ($mkt_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }
}

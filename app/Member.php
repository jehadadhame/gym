<?php

namespace App;

use DB;
use Carbon\Carbon;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Member extends Model implements HasMediaConversions
{
    use HasMediaTrait, Eloquence;
    use createdByUser, updatedByUser;

    protected $table = 'mst_members';

    protected $fillable = [
        'id',
        'member_code',
        'name',
        'DOB',
        'email',
        'address',
        'status',
        'proof_name',
        'gender',
        'contact',
        'emergency_contact',
        'health_issues',
        'pin_code',
        'occupation',
        'aim',
        'source',
        'created_by',
        'updated_by',
        'note'
    ];

    protected $dates = ['created_at', 'updated_at', 'DOB'];

    protected $searchableColumns = [
        'member_code' => 20,
        'name' => 20,
        'email' => 20,
        'contact' => 20,
        'emergency_contact' => 20,
    ];

    public function getDobAttribute($value)
    {
        return (new Carbon($value))->format('Y-m-d');
    }

    // Media i.e. Image size conversion
    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')->setManipulations(['w' => 50, 'h' => 50, 'q' => 100, 'fit' => 'crop'])->performOnCollections('profile');

        $this->addMediaConversion('form')->setManipulations(['w' => 70, 'h' => 70, 'q' => 100, 'fit' => 'crop'])->performOnCollections('profile', 'proof');
    }

    //Relationships
    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }

    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    //Scope Queries
    public function scopeIndexQuery($query, $sorting_field, $sorting_direction, $drp_start, $drp_end, $search = null)
    {
        // defaults
        $sorting_field = $sorting_field ?: 'pending_sum';
        $sorting_direction = in_array(strtolower($sorting_direction), ['asc', 'desc']) ? $sorting_direction : 'desc';

        // base select with aggregated pending_sum
        $query = $query
            ->leftJoin('trn_invoice', 'trn_invoice.member_id', '=', 'mst_members.id')
            ->select([
                'mst_members.id',
                'mst_members.member_code',
                'mst_members.name',
                'mst_members.contact',
                'mst_members.created_at',
                'mst_members.status',
                'mst_members.note',
                DB::raw('COALESCE(SUM(trn_invoice.pending_amount), 0) AS pending_sum'),
            ])
            ->where('mst_members.status', '!=', \constStatus::Archive);

        // keep your member-created_at date range filter (doesn't filter invoices)
        if ($drp_start && $drp_end) {
            $query->whereBetween('mst_members.created_at', [$drp_start, $drp_end]);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mst_members.name', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.member_code', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.contact', 'LIKE', "%$search%");

            });
        }

        // group by all non-aggregated columns
        $query->groupBy(
            'mst_members.id',
            'mst_members.member_code',
            'mst_members.name',
            'mst_members.contact',
            'mst_members.created_at',
            'mst_members.status'
        );

        // allow sorting by the computed alias
        if ($sorting_field === 'pending_sum') {
            return $query->orderBy(DB::raw('pending_sum'), $sorting_direction);
        }

        // fully-qualify known columns to avoid "ambiguous column" errors
        $map = [
            'id' => 'mst_members.id',
            'created_at' => 'mst_members.created_at',
            'status' => 'mst_members.status',
            'name' => 'mst_members.name',
            'member_code' => 'mst_members.member_code',
            'contact' => 'mst_members.contact',
        ];

        $sorting_field = isset($map[$sorting_field]) ? $map[$sorting_field] : $sorting_field;

        return $query->orderBy($sorting_field, $sorting_direction);
    }


    public function scopeActive($query, $sortField = 'created_at', $sortDirection = 'desc', $drpStart = null, $drpEnd = null, $search = null)
    {
        $query->where('status', '=', \constStatus::Active);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mst_members.name', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.member_code', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.contact', 'LIKE', "%$search%");

            });
        }

        if (!empty($drpStart) && !empty($drpEnd)) {
            $query->whereBetween('created_at', [$drpStart, $drpEnd]);
        }

        $query->orderBy($sortField, $sortDirection);

        return $query;
    }

    public function scopeInactive($query, $sortField = 'created_at', $sortDirection = 'desc', $drpStart = null, $drpEnd = null, $search = null)
    {
        $query->where('status', '=', \constStatus::InActive);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mst_members.name', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.member_code', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.contact', 'LIKE', "%$search%");

            });
        }

        if (!empty($drpStart) && !empty($drpEnd)) {
            $query->whereBetween('created_at', [$drpStart, $drpEnd]);
        }

        $query->orderBy($sortField, $sortDirection);

        return $query;
    }

    public function scopeRecent($query)
    {
        return $query->where('created_at', '<=', Carbon::today())->take(10)->orderBy('created_at', 'desc');
    }

    public function scopeBirthday($query)
    {
        return $query->whereMonth('DOB', '=', Carbon::today()->month)->whereDay('DOB', '<', Carbon::today()->addDays(7))->whereDay('DOB', '>=', Carbon::today()->day)->where('status', '=', \constStatus::Active);
    }

    // Laravel issue: Workaroud Needed
    public function scopeRegistrations($query, $month, $year)
    {
        return $query->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->count();
    }
}

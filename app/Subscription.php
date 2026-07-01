<?php

namespace App;

use Carbon\Carbon;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //Eloquence Search mapping
    use Eloquence;
    use createdByUser, updatedByUser;

    protected $table = 'trn_subscriptions';

    protected $fillable = [
        'id',
        'member_id',
        'invoice_id',
        'plan_id',
        'status',
        'is_renewal',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date'];

    protected $searchableColumns = [
        'member.member_code' => 20,
        'start_date' => 20,
        'end_date' => 20,
        'member.name' => 20,
        'member.DOB' => 20,
        'Plan.plan_name' => 20,
        'Invoice.invoice_number' => 20,
    ];

    public function scopeDashboardExpiring($query)
    {
        return $query
            ->with(['member' => function ($query) {
                $query->where('status', '=', \constStatus::Active);
            }])
            ->where('end_date', '<', Carbon::today()->addDays(7))
            ->where('status', '=', \constSubscription::onGoing);
    }

    public function scopeDashboardExpired($query)
    {
        return $query
            ->with(['member' => function ($query) {
                $query->where('status', '=', \constStatus::Active);
            }])
            ->where('status', '=', \constSubscription::Expired)
            ->where('plan_id', '=', 6)
            ->where('plan_id', '=', 4);
    }

    public function scopeIndexQuery($query, $sorting_field = null, $sorting_direction = null, $plan_id = null, $drp_start = null, $drp_end = null, $search = null)
    {
        $sorting_field = ($sorting_field != null ? $sorting_field : 'id');
        $sorting_direction = ($sorting_direction != null ? $sorting_direction : 'desc');

        // Join with plans table
        $query->leftJoin('mst_plans', 'trn_subscriptions.plan_id', '=', 'mst_plans.id')
            ->leftJoin('mst_members', 'trn_subscriptions.member_id', '=', 'mst_members.id')
            ->leftJoin('trn_invoice', 'trn_subscriptions.invoice_id', '=', 'trn_invoice.id')
            ->select(
                'trn_subscriptions.*',
                'mst_plans.plan_name',
                'mst_members.name as member_name',
                'mst_members.member_code',
                'mst_members.DOB',
                'trn_invoice.invoice_number'
            );

        // Filter by plan if provided
        if ($plan_id != null && $plan_id) {
            $query->where('trn_subscriptions.plan_id', '=', $plan_id);
        }

        // Search logic
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mst_members.name', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.member_code', 'LIKE', "%{$search}%")
                    ->orWhere('mst_plans.plan_name', 'LIKE', "%{$search}%")
                    ->orWhere('trn_invoice.invoice_number', 'LIKE', "%{$search}%")
                    ->orWhere('trn_subscriptions.start_date', 'LIKE', "%{$search}%")
                    ->orWhere('trn_subscriptions.end_date', 'LIKE', "%{$search}%");
            });
        }

        // Date range filter
        if ($drp_start != null && $drp_end != null) {
            $query->whereBetween('trn_subscriptions.created_at', [$drp_start, $drp_end]);
        }

        // Sorting
        $query->orderBy($sorting_field, $sorting_direction);

        return $query;
    }
    public function scopeExpiring($query, $sorting_field = null, $sorting_direction = null, $drp_start = null, $drp_end = null, $search = null)
    {
        $sorting_field = ($sorting_field != null ? $sorting_field : 'created_at');
        $sorting_direction = ($sorting_direction != null ? $sorting_direction : 'desc');

        $query->leftJoin('mst_plans', 'trn_subscriptions.plan_id', '=', 'mst_plans.id')
            ->leftJoin('mst_members', 'trn_subscriptions.member_id', '=', 'mst_members.id')
            ->select(
                'trn_subscriptions.*',
                'mst_plans.plan_name',
                'mst_members.name as member_name',
                'mst_members.member_code',
                'mst_members.DOB',
                'mst_members.status as member_status'
            );

        $query->where('mst_members.status', '=', \constStatus::Active);

        $query->where('trn_subscriptions.end_date', '<', Carbon::today()->addDays(7))
            ->where('trn_subscriptions.status', '=', \constSubscription::onGoing);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mst_members.name', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.member_code', 'LIKE', "%{$search}%")
                    ->orWhere('mst_plans.plan_name', 'LIKE', "%{$search}%");
            });
        }

        if ($drp_start != null && $drp_end != null) {
            $query->whereBetween('trn_subscriptions.created_at', [$drp_start, $drp_end]);
        }

        $query->orderBy($sorting_field, $sorting_direction);

        return $query;
    }

    public function scopeExpired($query, $sorting_field = null, $sorting_direction = null, $drp_start = null, $drp_end = null, $search = null)
    {
        $sorting_field = ($sorting_field != null ? $sorting_field : 'end_date');
        $sorting_direction = ($sorting_direction != null ? $sorting_direction : 'desc');

        $query->leftJoin('mst_plans', 'trn_subscriptions.plan_id', '=', 'mst_plans.id')
            ->leftJoin('mst_members', 'trn_subscriptions.member_id', '=', 'mst_members.id')
            ->select(
                'trn_subscriptions.*',
                'mst_plans.plan_name',
                'mst_members.name as member_name',
                'mst_members.member_code',
                'mst_members.DOB'
            );

        $query->where('trn_subscriptions.status', '=', \constSubscription::Expired)
            ->where('trn_subscriptions.status', '!=', \constSubscription::renewed);

        $query->where('trn_subscriptions.plan_id', '!=', 6)
            ->where('trn_subscriptions.plan_id', '!=', 4);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mst_members.name', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.member_code', 'LIKE', "%{$search}%")
                    ->orWhere('mst_plans.plan_name', 'LIKE', "%{$search}%");
            });
        }

        if ($drp_start != null && $drp_end != null) {
            $query->whereBetween('trn_subscriptions.created_at', [$drp_start, $drp_end]);
        }

        $query->orderBy($sorting_field, $sorting_direction);

        return $query;
    }

    public function member()
    {
        return $this->belongsTo('App\Member', 'member_id');
    }

    public function plan()
    {
        return $this->belongsTo('App\Plan', 'plan_id');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Invoice', 'invoice_id');
    }
}

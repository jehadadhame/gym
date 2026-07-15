<?php

namespace App;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'trn_invoice';

    protected $fillable = [
        'id',
        'total',
        'pending_amount',
        'member_id',
        'note',
        'status',
        'tax',
        'additional_fees',
        'invoice_number',
        'discount_percent',
        'discount_amount',
        'discount_note',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['created_at', 'updated_at'];

    //Eloquence Search mapping
    use Eloquence;
    use createdByUser, updatedByUser;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new \App\Scopes\GenderScope);
    }

    protected $searchableColumns = [
        'invoice_number' => 20,
        'total' => 20,
        'pending_amount' => 20,
        'Member.name' => 15,
        'discount_note' => 15,
        'Member.member_code' => 10,
    ];

    public function scopeIndexQuery($query, $sorting_field = null, $sorting_direction = null, $drp_start = null, $drp_end = null, $search = null)
    {
        $sorting_field = ($sorting_field != null ? $sorting_field : 'trn_invoice.id');
        $sorting_direction = ($sorting_direction != null ? $sorting_direction : 'desc');

        $query->leftJoin('mst_members', 'trn_invoice.member_id', '=', 'mst_members.id')
            ->select(
                'trn_invoice.*',
                'mst_members.name as member_name',
                'mst_members.member_code',
                'mst_members.contact'
            );

        // Search logic
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('trn_invoice.invoice_number', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.name', 'LIKE', "%{$search}%")
                    ->orWhere('trn_invoice.total', 'LIKE', "%{$search}%");
            });
        }

        // Date range filter
        if ($drp_start != null && $drp_end != null) {
            $query->whereBetween('trn_invoice.created_at', [$drp_start, $drp_end]);
        }

        // Sorting (only if sorting field is provided - skip for aggregate queries)
        if ($sorting_field && $sorting_direction) {
            $query->orderBy($sorting_field, $sorting_direction);
        }

        return $query;
    }
    public function member()
    {
        return $this->belongsTo('App\Member', 'member_id');
    }

    public function paymentDetails()
    {
        return $this->hasMany('App\PaymentDetail');
    }

    public function invoiceDetails()
    {
        return $this->hasMany('App\InvoiceDetail');
    }

    public function subscription()
    {
        return $this->hasOne('App\Subscription');
    }

    public function purchase()
    {
        return $this->hasOne('App\Purchase');
    }
}

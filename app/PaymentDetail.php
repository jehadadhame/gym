<?php

namespace App;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    //Eloquence Search mapping
    use Eloquence;
    use createdByUser, updatedByUser;

    protected $table = 'trn_payment_details';

    protected $fillable = [
        'payment_amount',
        'note',
        'mode',
        'invoice_id',
        'created_by',
        'updated_by',
    ];

    protected $searchableColumns = [
        'payment_amount' => 20,
        'Invoice.invoice_number' => 20,
        'Invoice.member.name' => 20,
        'Invoice.member.id' => 20,
    ];

    public function scopeIndexQuery($query, $sorting_field, $sorting_direction, $drp_start, $drp_end, $search)
    {
        $sorting_field = ($sorting_field != null ? $sorting_field : 'created_at');
        $sorting_direction = ($sorting_direction != null ? $sorting_direction : 'desc');

        // Join tables
        $query->leftJoin('trn_invoice', 'trn_payment_details.invoice_id', '=', 'trn_invoice.id')
            ->leftJoin('mst_members', 'trn_invoice.member_id', '=', 'mst_members.id')
            ->select(
                'trn_payment_details.id',
                'trn_payment_details.created_at',
                'trn_payment_details.payment_amount',
                'trn_payment_details.mode',
                'trn_payment_details.invoice_id',
                'trn_invoice.invoice_number',
                'mst_members.id as member_id',
                'mst_members.name as member_name',
                'mst_members.member_code'
            );

        // Search logic
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mst_members.name', 'LIKE', "%{$search}%")
                    ->orWhere('trn_invoice.invoice_number', 'LIKE', "%{$search}%")
                    ->orWhere('trn_payment_details.payment_amount', 'LIKE', "%{$search}%");
            });
        }

        // Date range filter
        if ($drp_start != null && $drp_end != null) {
            $query->whereBetween('trn_payment_details.created_at', [$drp_start, $drp_end]);
        }

        // Sorting
        $query->orderBy($sorting_field, $sorting_direction);

        return $query;
    }
    public function invoice()
    {
        return $this->belongsTo('App\Invoice', 'invoice_id');
    }

    public function cheque()
    {
        return $this->hasOne('App\ChequeDetail', 'payment_id');
    }
}

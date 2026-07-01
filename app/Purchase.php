<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Purchase extends Model
{
    protected $table = 'purchases';
    protected $fillable = [
        "member_id",
        "product_id",
        "invoice_id",
        'created_by',
        'updated_by',
    ];

    protected $dates = ['created_at', 'updated_at'];

    use Eloquence;
    use createdByUser, updatedByUser;

    protected $searchableColumns = [
        'Member.member_code' => 20,
        'Member.name' => 20,
        'Product.product_name' => 20,
        'Invoice.invoice_number' => 20,
    ];

    public function scopeIndexQuery($query, $sorting_field = null, $sorting_direction = null, $product_ids = null, $drp_start = null, $drp_end = null, $search = null)
    {
        $sorting_field = $sorting_field ?: 'purchases.id';
        $sorting_direction = $sorting_direction  ?: 'desc';

        $query->leftJoin('products', 'purchases.product_id', '=', 'products.id')
            ->leftJoin('mst_members', 'purchases.member_id', '=', 'mst_members.id')
            ->leftJoin('trn_invoice', 'purchases.invoice_id', '=', 'trn_invoice.id')
            ->select(
                'purchases.*',
                'products.product_name',
                'mst_members.name as member_name',
                'mst_members.member_code',
                'trn_invoice.invoice_number'
            );

        if (!empty($product_ids)) {
            if (is_array($product_ids)) {
                $query->whereIn('purchases.product_id', $product_ids);
            } else {
                $query->where('purchases.product_id', $product_ids);
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mst_members.name', 'LIKE', "%{$search}%")
                    ->orWhere('mst_members.member_code', 'LIKE', "%{$search}%")
                    ->orWhere('products.product_name', 'LIKE', "%{$search}%")
                    ->orWhere('trn_invoice.invoice_number', 'LIKE', "%{$search}%");
            });
        }

        if ($drp_start && $drp_end) {
            $query->whereBetween('purchases.created_at', [$drp_start, $drp_end]);
        }

        return $query->orderBy($sorting_field, $sorting_direction);
    }

    public function member()
    {
        return $this->belongsTo('App\Member', 'member_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Invoice', 'invoice_id');
    }
    //
}

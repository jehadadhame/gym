<?php

namespace App;

use Carbon\Carbon;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //Eloquence Search mapping
    use Eloquence;
    use createdByUser, updatedByUser;

    protected $table = 'trn_expenses';

    protected $fillable = [
        'name',
        'category_id',
        'amount',
        'due_date',
        'repeat',
        'note',
        'paid',
        'created_by',
        'updated_by',
    ];

    protected $searchableColumns = [
        'name' => 20,
        'amount' => 10,
    ];

    protected $dates = ['created_at', 'updated_at', 'due_date'];

    public function category()
    {
        return $this->belongsTo('App\ExpenseCategory', 'category_id');
    }

    public function scopeDueAlerts($query)
    {
        return $query->where('paid', '!=', \constPaymentStatus::Paid)->where('due_date', '>=', Carbon::today());
    }

    public function scopeOutstandingAlerts($query)
    {
        return $query->where('paid', '!=', \constPaymentStatus::Paid)->where('due_date', '<', Carbon::today());
    }

    public function scopeIndexQuery($query, $category = null, $sorting_field = null, $sorting_direction = null, $drp_start = null, $drp_end = null, $search = null)
    {
        $sorting_field = ($sorting_field != null ? $sorting_field : 'trn_expenses.created_at');
        $sorting_direction = ($sorting_direction != null ? $sorting_direction : 'desc');

        $query->leftJoin('mst_expenses_categories', 'trn_expenses.category_id', '=', 'mst_expenses_categories.id')
            ->select(
                'trn_expenses.*',
                'mst_expenses_categories.name as category_name'
            );

        if ($category != null && $category != 0) {
            $query->where('trn_expenses.category_id', $category);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('trn_expenses.name', 'LIKE', "%{$search}%")
                    ->orWhere('trn_expenses.amount', 'LIKE', "%{$search}%")
                    ->orWhere('mst_expenses_categories.name', 'LIKE', "%{$search}%");
            });
        }

        if ($drp_start != null && $drp_end != null) {
            $query->whereBetween('trn_expenses.created_at', [$drp_start, $drp_end]);
        }

        $query->orderBy($sorting_field, $sorting_direction);

        return $query;
    }
}

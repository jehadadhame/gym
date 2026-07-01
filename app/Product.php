<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Searchable\Searchable;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'product_code',
        'product_name',
        'product_details',
        'amount',
        'status',
        'created_by',
        'updated_by',
    ];
    use Eloquence;
    use createdByUser, updatedByUser;

    protected $searchableColumns = [
        'product_code' => 20,
        'product_name' => 10,
        'product_details' => 5,
    ];
    public function scopeExcludeArchive($query)
    {
        return $query->where('status', '!=', \constStatus::Archive);
    }

    public function scopeOnlyActive($query)
    {
        return $query->where('status', '=', \constStatus::Active);
    }

    public function purchases()
    {
        return $this->hasMany('\App\Purchase', 'product_id');
    }
}

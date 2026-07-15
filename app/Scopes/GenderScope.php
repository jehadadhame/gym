<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;
use Illuminate\Support\Facades\Auth;

class GenderScope implements ScopeInterface
{
    public function apply(Builder $builder, Model $model)
    {
        if (app()->runningInConsole()) {
            return;
        }

        if (Auth::check()) {
            $user = Auth::user();

            // Admin bypasses the check
            if ($user->hasRole('Admin')) {
                return;
            }

            $canManageFemale = $user->can('manage-female-shift');
            $canManageMale = $user->can('manage-male-shift');

            if ($canManageFemale && $canManageMale) {
                // If they have both, they can see both. No scope needed.
                return;
            } elseif ($canManageFemale) {
                // Filter female only
                $this->applyFilter($builder, $model, 'f');
            } elseif ($canManageMale) {
                // Filter male only
                $this->applyFilter($builder, $model, 'm');
            } else {
                // If they have neither permission, we allow them to see everything by default.
                // Alternatively, if you want them to see nothing by default, change this back to $builder->whereRaw('1 = 0');
                return;
            }
        }
    }

    public function remove(Builder $builder, Model $model)
    {
        // Optional implementation for withoutGlobalScope()
        $query = $builder->getQuery();
        $table = $model->getTable();
        
        $bindings = $query->getRawBindings()['where'];
        
        // Complex removal logic can go here. For now we leave it empty to just satisfy the interface.
        // It's mostly needed if you intend to use Member::withoutGlobalScope(new GenderScope)->get()
    }

    protected function applyFilter(Builder $builder, Model $model, $genderValue)
    {
        $table = $model->getTable();
        
        if ($table === 'mst_members' || $table === 'mst_enquiries') {
            $builder->where($table . '.gender', $genderValue);
        } elseif ($table === 'trn_invoice' || $table === 'trn_subscriptions') {
            $builder->whereHas('member', function ($q) use ($genderValue) {
                $q->where('gender', $genderValue);
            });
        } elseif ($table === 'trn_payment_details') {
            $builder->whereHas('invoice.member', function ($q) use ($genderValue) {
                $q->where('gender', $genderValue);
            });
        }
    }
}

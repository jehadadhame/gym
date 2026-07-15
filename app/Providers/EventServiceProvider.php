<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        $tracker = function ($model, $action) {
            $class = class_basename($model);
            $allowed = ['Member', 'Subscription', 'Invoice', 'PaymentDetail', 'Purchase'];
            if (!in_array($class, $allowed)) {
                return;
            }

            $user = \Auth::user();
            $userId = $user ? $user->id : null;

            // Arabic translations
            $actions = [
                'created' => 'إنشاء',
                'updated' => 'تعديل',
                'deleted' => 'حذف'
            ];
            $actionAr = $actions[$action] ?? $action;

            $entities = [
                'Member' => 'عضو',
                'Subscription' => 'اشتراك',
                'Invoice' => 'فاتورة',
                'PaymentDetail' => 'دفعة',
                'Purchase' => 'عملية شراء لمنتج'
            ];
            $entityAr = $entities[$class] ?? $class;

            $details = '';
            if ($class === 'Member') {
                $details = $model->name ?? '';
                if ($action === 'updated') {
                    $dirty = $model->getDirty();
                    unset($dirty['updated_at']);
                    if (count($dirty) > 0) {
                        $fieldNames = [
                            'name' => 'الاسم',
                            'email' => 'البريد الإلكتروني',
                            'contact' => 'الهاتف',
                            'DOB' => 'تاريخ الميلاد',
                            'address' => 'العنوان',
                            'gender' => 'الجنس',
                            'emergency_contact' => 'هاتف الطوارئ',
                            'health_issues' => 'مشاكل صحية',
                            'proof_name' => 'نوع الإثبات',
                            'status' => 'الحالة',
                            'member_code' => 'كود العضو',
                            'pin_code' => 'الرمز البريدي',
                            'occupation' => 'المهنة',
                            'aim' => 'الهدف'
                        ];
                        $changed = array_map(function($key) use ($fieldNames) {
                            return $fieldNames[$key] ?? $key;
                        }, array_keys($dirty));
                        
                        $details .= ' (تحديث: ' . implode('، ', $changed) . ')';
                    }
                }
            } elseif ($class === 'Subscription') {
                $details = 'للعضو ' . ($model->member ? $model->member->name : 'غير معروف');
            } elseif ($class === 'Invoice') {
                $details = 'رقم ' . ($model->invoice_number ?? $model->id);
            } elseif ($class === 'PaymentDetail') {
                $details = 'بمبلغ ' . ($model->payment_amount ?? 'غير معروف') . ' ₪';
            } elseif ($class === 'Purchase') {
                $details = ($model->product ? $model->product->product_name : 'غير معروف');
            }

            $story = "{$actionAr} {$entityAr} {$details}";

            $affected_member_name = '';
            if ($class === 'Member') {
                $affected_member_name = $model->name;
            } elseif (in_array($class, ['Subscription', 'Invoice', 'PaymentDetail'])) {
                if ($model->member) {
                    $affected_member_name = $model->member->name;
                } elseif ($class === 'PaymentDetail' && $model->invoice && $model->invoice->member) {
                    $affected_member_name = $model->invoice->member->name;
                }
            } elseif ($class === 'Purchase') {
                if ($model->member) {
                    $affected_member_name = $model->member->name;
                }
            }

            // Group transactions that happen within 5 seconds of each other by the same user
            $lastTracker = \App\ActivityTracker::where('user_id', $userId)->orderBy('id', 'desc')->first();
            
            if ($lastTracker && $lastTracker->created_at && $lastTracker->created_at->diffInSeconds(\Carbon\Carbon::now()) <= 5) {
                // Append to existing story
                $lastTracker->story .= "، و" . trim($story);
                if (empty($lastTracker->affected_member_name) && !empty($affected_member_name)) {
                    $lastTracker->affected_member_name = $affected_member_name;
                }
                $lastTracker->save();
            } else {
                // Create new log entry
                \App\ActivityTracker::create([
                    'user_id' => $userId,
                    'action_type' => $actionAr,
                    'affected_member_name' => $affected_member_name,
                    'story' => trim($story)
                ]);
            }
        };

        \App\Member::created(function($model) use ($tracker) { $tracker($model, 'created'); });
        \App\Member::updating(function($model) use ($tracker) { $tracker($model, 'updated'); });
        \App\Member::deleted(function($model) use ($tracker) { $tracker($model, 'deleted'); });

        \App\Subscription::created(function($model) use ($tracker) { $tracker($model, 'created'); });
        \App\Subscription::updated(function($model) use ($tracker) { $tracker($model, 'updated'); });
        \App\Subscription::deleted(function($model) use ($tracker) { $tracker($model, 'deleted'); });

        \App\Invoice::created(function($model) use ($tracker) { $tracker($model, 'created'); });
        \App\Invoice::updated(function($model) use ($tracker) { $tracker($model, 'updated'); });
        \App\Invoice::deleted(function($model) use ($tracker) { $tracker($model, 'deleted'); });

        \App\PaymentDetail::created(function($model) use ($tracker) { $tracker($model, 'created'); });
        \App\PaymentDetail::updated(function($model) use ($tracker) { $tracker($model, 'updated'); });
        \App\PaymentDetail::deleted(function($model) use ($tracker) { $tracker($model, 'deleted'); });

        \App\Purchase::created(function($model) use ($tracker) { $tracker($model, 'created'); });
        \App\Purchase::updated(function($model) use ($tracker) { $tracker($model, 'updated'); });
        \App\Purchase::deleted(function($model) use ($tracker) { $tracker($model, 'deleted'); });
    }
}

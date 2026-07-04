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
            $allowed = ['Member', 'Subscription', 'Invoice', 'PaymentDetail'];
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
                'PaymentDetail' => 'دفعة'
            ];
            $entityAr = $entities[$class] ?? $class;

            $details = '';
            if ($class === 'Member') {
                $details = $model->name ?? '';
            } elseif ($class === 'Subscription') {
                $details = 'للعضو ' . ($model->member ? $model->member->name : 'غير معروف');
            } elseif ($class === 'Invoice') {
                $details = 'رقم ' . ($model->invoice_number ?? $model->id);
            } elseif ($class === 'PaymentDetail') {
                $details = 'بمبلغ ' . ($model->payment_amount ?? 'غير معروف');
            }

            $story = "{$actionAr} {$entityAr} {$details}";

            // Group transactions that happen within 5 seconds of each other by the same user
            $lastTracker = \App\ActivityTracker::where('user_id', $userId)->orderBy('id', 'desc')->first();
            
            if ($lastTracker && $lastTracker->created_at && $lastTracker->created_at->diffInSeconds(\Carbon\Carbon::now()) <= 5) {
                // Append to existing story
                $lastTracker->story .= "، و" . trim($story);
                $lastTracker->save();
            } else {
                // Create new log entry
                \App\ActivityTracker::create([
                    'user_id' => $userId,
                    'story' => trim($story)
                ]);
            }
        };

        \App\Member::created(function($model) use ($tracker) { $tracker($model, 'created'); });
        \App\Member::updated(function($model) use ($tracker) { $tracker($model, 'updated'); });
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
    }
}

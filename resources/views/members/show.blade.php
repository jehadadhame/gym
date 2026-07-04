@extends('app')

@section('content')
<?php use Carbon\Carbon; ?>

<div class="rightside bg-grey-100">
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
    </div>
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="panel bg-white">
                    <div class="panel-title">
                        <div class="panel-head font-size-20">ملف العضو</div>
                        <div class="pull-right no-margin">
                            @permission(['manage-gymie', 'manage-members', 'edit-member'])
                            <a class="btn btn-primary" href="{{ action('MembersController@edit',['id' => $member->id]) }}">
                                <span>تعديل</span>
                            </a>
                            @endpermission

                            @permission(['manage-gymie', 'manage-members', 'delete-member'])
                            <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{$member->id}}" data-id="{{$member->id}}">
                                <span>حذف</span>
                            </button>
                            @endpermission

                            <!-- Modal -->
                            <div id="deleteModal-{{$member->id}}" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">تأكيد الحذف</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>هل أنت متأكد من أنك تريد حذف هذا العضو؟</p>
                                        </div>
                                        <div class="modal-footer">
                                            {!! Form::Open(['action' => ['MembersController@archive', $member->id], 'method' => 'POST', 'id' => 'archiveform-' . $member->id]) !!}
                                            <input type="submit" class="btn btn-danger" value="نعم" id="btn-{{ $member->id }}" />
                                            <button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
                                            {!! Form::Close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body padding-20">
                        <div class="row">
                            <!-- Left Column: Gym & Financial Details -->
                            <div class="col-sm-4 pull-left">
                                <h4 class="form-section-title">تفاصيل النادي</h4>
                                <?php
                                $subscriptions = $member->subscriptions;
                                $plansArray = array();
                                foreach ($subscriptions as $subscription) {
                                    if ($subscription->status == \constSubscription::onGoing) {
                                        $plansArray[] = $subscription->plan->plan_name;
                                    }
                                }
                                $activePlanDisplay = empty($plansArray) ? 'لا توجد خطة نشطة' : implode(", ", $plansArray);
                                ?>
                                <div class="row">
                                    <div class="col-sm-8"><span class="show-data">{{$activePlanDisplay}}</span></div>
                                    <div class="col-sm-4"><label class="color-text-secondary font-weight-600">الخطة النشطة</label></div>
                                </div>
                                <hr class="margin-top-10 margin-bottom-10">
                                
                                <div class="row">
                                    <div class="col-sm-8"><span class="show-data">{{Utilities::getStatusValue($member->status)}}</span></div>
                                    <div class="col-sm-4"><label class="color-text-secondary font-weight-600">الحالة</label></div>
                                </div>
                                <hr class="margin-top-10 margin-bottom-10">

                                <div class="row">
                                    <div class="col-sm-8">
                                        @if($pending_amount > 0)
                                            <span class="label label-danger font-size-14">{{$pending_amount}}</span>
                                        @else
                                            <span class="label label-success font-size-14">{{$pending_amount}}</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-4"><label class="color-text-secondary font-weight-600">المبلغ المتبقي</label></div>
                                </div>
                                <hr class="margin-top-10 margin-bottom-10">

                                <div class="row">
                                    <div class="col-sm-8"><span class="show-data">{{Utilities::getAim($member->aim)}}</span></div>
                                    <div class="col-sm-4"><label class="color-text-secondary font-weight-600">الهدف</label></div>
                                </div>
                                <hr class="margin-top-10 margin-bottom-10">

                                <div class="row">
                                    <div class="col-sm-8"><span class="show-data">{{$member->health_issues}}</span></div>
                                    <div class="col-sm-4"><label class="color-text-secondary font-weight-600">حالة صحية</label></div>
                                </div>
                            </div>

                            <!-- Right Column: Personal Info & Image -->
                            <div class="col-sm-8 pull-right">
                                <h4 class="form-section-title">البيانات الشخصية</h4>
                                <div class="row">
                                    <div class="col-sm-9 pull-right">
                                        <div class="row">
                                            <div class="col-sm-8"><span class="show-data font-size-18 font-weight-700">{{$member->name}}</span></div>
                                            <div class="col-sm-4"><label class="color-text-secondary font-weight-600">الاسم</label></div>
                                        </div>
                                        <hr class="margin-top-10 margin-bottom-10">

                                        <div class="row">
                                            <div class="col-sm-8"><span class="show-data">{{$member->member_code}}</span></div>
                                            <div class="col-sm-4"><label class="color-text-secondary font-weight-600">الكود</label></div>
                                        </div>
                                        <hr class="margin-top-10 margin-bottom-10">

                                        <div class="row">
                                            <div class="col-sm-8">
                                                <span class="show-data">{{$member->contact}}</span>
                                                <div class="margin-top-10">
                                                    <a href="https://wa.me/send/?phone=%2B972{{ $member->contact }}&text=مرحبا {{ $member->name }}، يسعد مساك %0A اشتراكك انتهى قبل كم يوم بتحب أجدده؟"
                                                       class="btn btn-sm btn-success margin-right-5" target="_blank">
                                                        <i class="fa fa-whatsapp"></i> واتساب (+972)
                                                    </a>
                                                    <a href="https://wa.me/send/?phone=%2B970{{ $member->contact }}&text=مرحبا {{ $member->name }}، يسعد مساك %0A اشتراكك انتهى قبل كم يوم بتحب أجدده؟"
                                                       class="btn btn-sm btn-success" target="_blank">
                                                        <i class="fa fa-whatsapp"></i> واتساب (+970)
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-sm-4"><label class="color-text-secondary font-weight-600">الجوال</label></div>
                                        </div>
                                        <hr class="margin-top-10 margin-bottom-10">

                                        <div class="row">
                                            <div class="col-sm-8"><span class="show-data">{{$member->DOB}}</span></div>
                                            <div class="col-sm-4"><label class="color-text-secondary font-weight-600">تاريخ الميلاد</label></div>
                                        </div>
                                        <hr class="margin-top-10 margin-bottom-10">

                                        <div class="row">
                                            <div class="col-sm-8"><span class="show-data">{{$member->created_at->format('Y-m-d')}}</span></div>
                                            <div class="col-sm-4"><label class="color-text-secondary font-weight-600">تاريخ الانضمام</label></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3 pull-left">
                                        <?php
                                        $images = $member->getMedia('profile');
                                        $profileImage = ($images->isEmpty() ? 'https://placeholdit.imgix.net/~text?txtsize=22&txt=NA&w=200&h=180' : url($images[0]->getUrl()));
                                        ?>
                                        <img class="img-responsive img-thumbnail margin-bottom-10" style="border-radius: var(--radius-lg); width: 100%;" src="{{ $profileImage }}" />
                                        <div class="text-center">
                                            <span class="label label-info">{{Utilities::getGender($member->gender)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Of Main Row -->
                    </div>
                </div>
            </div>
        </div>

        <!-- History Tables (Combined) -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel bg-white">
                    <div class="panel-body padding-20">
                        <h4 class="form-section-title">سجل الاشتراكات</h4>
                        <table class="table table-bordered table-striped" dir="rtl">
                            <thead>
                                <tr>
                                    <th class="text-right">رقم الفاتورة</th>
                                    <th class="text-right">اسم الخطة</th>
                                    <th class="text-right">تاريخ البدء</th>
                                    <th class="text-right">تاريخ الانتهاء</th>
                                    <th class="text-right">حالة الاشتراك</th>
                                    <th class="text-right">حالة الدفع</th>
                                    <th class="text-right">المبلغ المتبقي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($member->subscriptions->sortByDesc('created_at') as $subscription)
                                    <tr>
                                        <td>
                                            <a href="{{ action('InvoicesController@show',['id' => $subscription->invoice_id]) }}">{{ $subscription->invoice->invoice_number }}</a>
                                        </td>
                                        <td>{{ $subscription->plan->plan_name }}</td>
                                        <td>{{ $subscription->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $subscription->end_date->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="{{ Utilities::getSubscriptionLabel($subscription->status) }}">{{ Utilities::getSubscriptionStatus($subscription->status) }}</span>
                                        </td>
                                        <td>
                                            <span class="{{ Utilities::getInvoiceLabel($subscription->invoice->status) }}">{{ Utilities::getInvoiceStatus($subscription->invoice->status) }}</span>
                                        </td>
                                        <td>
                                            @if($subscription->invoice->pending_amount > 0)
                                                <span class="label label-danger">{{$subscription->invoice->pending_amount }}</span>
                                            @else
                                                <span class="label label-success">{{$subscription->invoice->pending_amount }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h4 class="form-section-title margin-top-40">سجل المشتريات</h4>
                        <table class="table table-bordered table-striped" dir="rtl">
                            <thead>
                                <tr>
                                    <th class="text-right">رقم الفاتورة</th>
                                    <th class="text-right">اسم المنتج</th>
                                    <th class="text-right">حالة الدفع</th>
                                    <th class="text-right">المبلغ المتبقي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($member->purchases->sortByDesc('created_at') as $purchase)
                                    <tr>
                                        <td>
                                            <a href="{{ action('InvoicesController@show',['id' => $purchase->invoice_id]) }}">{{ $purchase->invoice->invoice_number }}</a>
                                        </td>
                                        <td>{{ $purchase->product->product_name }}</td>
                                        <td>
                                            <span class="{{ Utilities::getInvoiceLabel($purchase->invoice->status) }}">{{ Utilities::getInvoiceStatus($purchase->invoice->status) }}</span>
                                        </td>
                                        <td>
                                            @if($purchase->invoice->pending_amount > 0)
                                                <span class="label label-danger">{{ $purchase->invoice->pending_amount }}</span>
                                            @else
                                                <span class="label label-success">{{ $purchase->invoice->pending_amount }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
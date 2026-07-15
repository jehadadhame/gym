@extends('app')

@section('content')
<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
        <h1 class="page-title no-line-height">الاشتراكات
            @permission(['manage-gymie', 'manage-subscriptions', 'add-subscription'])
            <a href="{{ action('SubscriptionsController@create') }}" class="page-head-btn btn-sm btn-primary active"
                role="button">إضافة جديد</a>
            @endpermission
            <small>تفاصيل جميع اشتراكات الصالة</small>
        </h1>
        @permission(['manage-gymie', 'pagehead-stats'])
        <h1 class="font-size-30 text-right color-primary-500 animated fadeInDown total-count pull-right"><span
                data-toggle="counter" data-start="0" data-from="0" data-to="{{ $count }}" data-speed="600"
                data-refresh-interval="10"></span>
            <small class="color-text-secondary display-block margin-top-5 font-size-14">إجمالي الاشتراكات</small>
        </h1>
        @endpermission
    </div><!-- / PageHead -->

    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel bg-white">

                    <!-- Filter Bar -->
                    <div class="panel-heading bg-white" style="border-bottom: 1px solid var(--color-border-light); padding: 15px 20px;">
                        {!! Form::Open(['method' => 'GET', 'class' => 'form-inline']) !!}
                        <div class="row" dir="rtl">
                            
                            <div class="col-md-3 col-sm-6 margin-bottom-10">
                                {!! Form::label('subscription-daterangepicker', 'نطاق التاريخ', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <div id="subscription-daterangepicker" class="form-control gymie-daterangepicker" style="cursor: pointer; width: 100%;">
                                    <i class="ion-calendar margin-right-10 color-primary-500"></i>
                                    <span>{{$drp_placeholder}}</span>
                                    <i class="ion-ios-arrow-down margin-left-5 pull-left"></i>
                                </div>
                                {!! Form::text('drp_start', null, ['class' => 'hidden', 'id' => 'drp_start']) !!}
                                {!! Form::text('drp_end', null, ['class' => 'hidden', 'id' => 'drp_end']) !!}
                            </div>

                            <div class="col-md-2 col-sm-6 margin-bottom-10">
                                {!! Form::label('sort_field', 'ترتيب حسب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                {!! Form::select('sort_field', array('created_at' => 'التاريخ', 'plan_name' => 'اسم الخطة'), old('sort_field'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_field', 'style' => 'width: 100%;']) !!}
                            </div>

                            <div class="col-md-2 col-sm-6 margin-bottom-10">
                                {!! Form::label('sort_direction', 'الترتيب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                {!! Form::select('sort_direction', array('desc' => 'تنازلي', 'asc' => 'تصاعدي'), old('sort_direction'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_direction', 'style' => 'width: 100%;']) !!}
                            </div>

                            <div class="col-md-2 col-sm-6 margin-bottom-10">
                                {!! Form::label('plan_name', 'اسم الخطة', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <?php $plans = App\Plan::all(); ?>
                                <select id="plan_id" name="plan_id" class="form-control selectpicker show-tick" style="width: 100%;">
                                    <option value="0" {{ (old('plan_id') == "" ? "selected" : "") }}>الكل</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ (old('plan_id') == $plan->id ? "selected" : "") }}>{{ $plan->plan_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-sm-6 margin-bottom-10">
                                {!! Form::label('search', 'كلمة البحث', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <input value="{{ old('search') }}" name="search" id="search" type="text" class="form-control" placeholder="بحث..." style="width: 100%;">
                            </div>

                            <div class="col-md-1 col-sm-12 margin-bottom-10 text-left" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i></button>
                            </div>

                        </div>
                        {!! Form::Close() !!}
                    </div>

                    <div class="panel-body padding-20">
                        @if($subscriptions->count() == 0)
                            <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لا توجد سجلات</h4>
                        @else
                            <div class="table-responsive">
                                <table id="subscriptions" class="table table-bordered table-striped" dir="rtl">
                                    <thead>
                                        <tr>
                                            <th class="text-right">كود العضو</th>
                                            <th class="text-right">اسم العضو</th>
                                            <th class="text-right">اسم الخطة</th>
                                            <th class="text-right">تاريخ البدء</th>
                                            <th class="text-right">تاريخ الانتهاء</th>
                                            <th class="text-right">الحالة</th>
                                            <th class="text-center">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subscriptions as $subscription)
                                            <tr>
                                                <td>
                                                    <a href="{{ action('MembersController@show',['id' => $subscription->member->id]) }}" class="font-weight-600 color-primary-500">{{ $subscription->member->member_code}}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ action('MembersController@show',['id' => $subscription->member->id]) }}" class="font-weight-600 color-text-primary">{{ $subscription->member->name}}</a>
                                                </td>
                                                <td>{{ $subscription->plan_name}}</td>
                                                <td dir="ltr" class="text-right">{{ $subscription->start_date->format('Y-m-d')}}</td>
                                                <td dir="ltr" class="text-right">{{ $subscription->end_date->format('Y-m-d')}}</td>
                                                <td>
                                                    <span class="{{ Utilities::getSubscriptionLabel($subscription->status) }}">{{ Utilities::getSubscriptionStatus($subscription->status) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            الإجراءات <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                            @permission(['manage-gymie', 'manage-subscriptions', 'renew-subscription'])
                                                            <li>
                                                                <a href="{{ action('SubscriptionsController@renew',['id' => $subscription->invoice_id]) }}">
                                                                    <i class="fa fa-refresh"></i> تجديد الاشتراك
                                                                </a>
                                                            </li>
                                                            @endpermission
                                                            <li>
                                                                @permission(['manage-gymie', 'manage-subscriptions', 'edit-subscription'])
                                                                <a href="{{ action('SubscriptionsController@edit',['id' => $subscription->id]) }}">
                                                                    <i class="fa fa-pencil"></i> تعديل التفاصيل
                                                                </a>
                                                                @endpermission
                                                            </li>
                                                            @permission(['manage-gymie', 'manage-subscriptions', 'change-subscription'])
                                                            <li>
                                                                <a href="{{ action('SubscriptionsController@change',['id' => $subscription->id]) }}">
                                                                    <i class="fa fa-exchange"></i> ترقية/تخفيض الخطة
                                                                </a>
                                                            </li>
                                                            @endpermission
                                                            <li class="divider"></li>
                                                            @permission(['manage-gymie', 'manage-subscriptions', 'delete-subscription'])
                                                            <li>
                                                                <a href="#" class="delete-record color-error-text" data-delete-url="{{ url('subscriptions/' . $subscription->id . '/delete') }}" data-record-id="{{$subscription->id}}">
                                                                    <i class="fa fa-trash"></i> حذف الاشتراك
                                                                </a>
                                                            </li>
                                                            @endpermission
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row margin-top-20">
                                <div class="col-xs-6">
                                    <div class="gymie_paging_info color-text-secondary">
                                        عرض الصفحة {{ $subscriptions->currentPage() }} من {{ $subscriptions->lastPage() }}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="gymie_paging pull-left">
                                        {!! str_replace('/?', '?', $subscriptions->appends(Input::all())->render()) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('footer_script_init')
<script type="text/javascript">
    $(document).ready(function () {
        gymie.deleterecord();
    });
</script>
@stop

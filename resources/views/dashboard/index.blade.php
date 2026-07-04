@extends('app')

@section('content')

    <div class="rightside bg-grey-100">

        <div class="container-fluid">
            @include('flash::message')
            
            @permission(['manage-gymie','view-dashboard-quick-stats'])
            <!-- TOP METRICS ROW -->
            <div class="row margin-top-20">
                
                <!-- Total Members -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel bg-white">
                        <div class="panel-body padding-15-20">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <div class="color-text-primary font-size-24 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0"
                                         data-to="{{ $totalMembers }}" data-speed="500" data-refresh-interval="10"></div>
                                </div>
                                <div class="pull-right">
                                    <i class="font-size-24 color-primary-500 fa fa-users"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div class="pull-left">
                                    <div class="display-block color-text-secondary font-weight-600">إجمالي الأعضاء</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Subscriptions -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel bg-white">
                        <div class="panel-body padding-15-20">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <div class="color-text-primary font-size-24 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0"
                                         data-to="{{ $activeSubscriptions }}" data-speed="500" data-refresh-interval="10"></div>
                                </div>
                                <div class="pull-right">
                                    <i class="font-size-24 color-primary-500 fa fa-check-square-o"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div class="pull-left">
                                    <div class="display-block color-text-secondary font-weight-600">الاشتراكات الفعالة</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel bg-white">
                        <div class="panel-body padding-15-20">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <div class="color-text-primary font-size-24 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0"
                                         data-to="{{ $monthlyRevenue }}" data-speed="500" data-refresh-interval="10"></div>
                                </div>
                                <div class="pull-right">
                                    <i class="font-size-24 color-primary-500 fa fa-money"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div class="pull-left">
                                    <div class="display-block color-text-secondary font-weight-600">إيرادات هذا الشهر</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Expenses -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel bg-white">
                        <div class="panel-body padding-15-20">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <div class="color-text-primary font-size-24 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0"
                                         data-to="{{ $monthlyExpenses }}" data-speed="500" data-refresh-interval="10"></div>
                                </div>
                                <div class="pull-right">
                                    <i class="font-size-24 color-primary-500 fa fa-credit-card"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div class="pull-left">
                                    <div class="display-block color-text-secondary font-weight-600">مصروفات هذا الشهر</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SECOND METRICS ROW (Operational Alerts) -->
            <div class="row">
                
                <!-- Registrations This Month -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel bg-white">
                        <div class="panel-body padding-15-20">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <div class="color-text-primary font-size-24 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0"
                                         data-to="{{ App\Member::whereMonth('created_at','=',date('m'))->whereYear('created_at','=',date('Y'))->count() }}" data-speed="500" data-refresh-interval="10"></div>
                                </div>
                                <div class="pull-right">
                                    <i class="font-size-24 color-primary-500 fa fa-bar-chart"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div class="pull-left">
                                    <div class="display-block color-text-secondary font-weight-600">تسجيلات هذا الشهر</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inactive Members -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel bg-white">
                        <div class="panel-body padding-15-20">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <div class="color-text-primary font-size-24 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0"
                                         data-to="{{ App\Member::where('status',0)->count() }}" data-speed="500" data-refresh-interval="10"></div>
                                </div>
                                <div class="pull-right">
                                    <i class="font-size-24 color-primary-500 fa fa-exclamation-circle"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div class="pull-left">
                                    <div class="display-block color-text-secondary font-weight-600">الأعضاء غير النشطين</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expired Subscriptions -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel bg-white">
                        <div class="panel-body padding-15-20">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <div class="color-text-primary font-size-24 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0"
                                         data-to="{{ App\Subscription::where('status', \constSubscription::Expired)->count() }}" data-speed="500" data-refresh-interval="10"></div>
                                </div>
                                <div class="pull-right">
                                    <i class="font-size-24 color-primary-500 fa fa-ban"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div class="pull-left">
                                    <div class="display-block color-text-secondary font-weight-600">الاشتراكات المنتهية</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Payments -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel bg-white">
                        <div class="panel-body padding-15-20">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <div class="color-text-primary font-size-24 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0"
                                         data-to="{{ App\Invoice::sum('pending_amount') }}" data-speed="500" data-refresh-interval="10"></div>
                                </div>
                                <div class="pull-right">
                                    <i class="font-size-24 color-primary-500 fa fa-money"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div class="pull-left">
                                    <div class="display-block color-text-secondary font-weight-600">المدفوعات المعلقة</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endpermission

            @permission(['manage-gymie','view-dashboard-charts'])
            <!-- CHARTS ROW -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel bg-white">
                        <div class="panel-title bg-transparent no-border">
                            <div class="panel-head">اتجاه التسجيل</div>
                        </div>
                        <div class="panel-body no-padding-top">
                            <div id="gymie-registrations-trend" class="chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="panel bg-white">
                        <div class="panel-title">
                            <div class="panel-head">الأعضاء حسب الخطة</div>
                        </div>
                        <div class="panel-body padding-top-10">
                            @if(!empty($membersPerPlan))
                                <div id="gymie-members-per-plan" class="chart"></div>
                            @else
                                <div class="tab-empty-panel font-size-24 color-grey-300">
                                    <div id="gymie-members-per-plan" class="chart"></div>
                                    لا توجد بيانات
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endpermission

            <!-- QUICK ACTIONS & TABS ROW -->
            <div class="row"> 
                <!-- LEFT COLUMN: Members & Enquiries -->
                <div class="col-lg-6">
                    @permission(['manage-gymie','view-dashboard-members-tab'])
                    <div class="panel">
                        <div class="panel-title">
                            <div class="panel-head"><i class="fa fa-users"></i><a href="{{ action('MembersController@index') }}">الأعضاء</a></div>
                            <div class="pull-right"><a href="{{ action('MembersController@create') }}" class="btn-sm btn-primary active" role="button"><i
                                            class="fa fa-user-plus"></i> إضافة</a></div>
                        </div>

                        <div class="panel-body with-nav-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#expiring" data-toggle="tab">ينتهي قريباً<span
                                                class="label label-warning margin-left-5">{{ $expiringCount }}</span></a></li>
                                <li><a href="#expired" data-toggle="tab">منتهي<span class="label label-danger margin-left-5">{{ $expiredCount }}</span></a>
                                </li>
                                <li><a href="#birthdays" data-toggle="tab">أعياد الميلاد<span class="label label-success margin-left-5">{{ $birthdayCount }}</span></a>
                                </li>
                                <li><a href="#recent" data-toggle="tab">الأحدث</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="expiring">
                                    @include('dashboard._index.expiring', ['expirings' => $expirings])
                                </div>
                                <div class="tab-pane fade" id="expired">
                                    @include('dashboard._index.expired', ['allExpired' => $allExpired])
                                </div>
                                <div class="tab-pane fade" id="birthdays">
                                    @include('dashboard._index.birthdays', ['birthdays' => $birthdays])
                                </div>
                                <div class="tab-pane fade" id="recent">
                                    @include('dashboard._index.recents', ['recents' =>  $recents])
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission

                    @permission(['manage-gymie','view-dashboard-enquiries-tab'])
                    <div class="panel">
                        <div class="panel-title">
                            <div class="panel-head"><i class="fa fa-phone"></i><a href="{{ action('EnquiriesController@index') }}">الاستفسارات</a></div>
                            <div class="pull-right"><a href="{{ action('EnquiriesController@create') }}" class="btn-sm btn-primary active" role="button"><i
                                            class="fa fa-phone"></i> إضافة</a></div>
                        </div>

                        <div class="panel-body with-nav-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#enquiries" data-toggle="tab">الاستفسارات</a></li>
                                <li><a href="#reminders" data-toggle="tab">التذكيرات<span class="label label-warning margin-left-5">{{ $reminderCount }}</span></a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="enquiries">
                                    @include('dashboard._index.enquiries', ['enquiries' => $enquiries])
                                </div>
                                <div class="tab-pane fade" id="reminders">
                                    @include('dashboard._index.reminders', ['reminders' => $reminders])
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                </div>

                <!-- RIGHT COLUMN: Finances & Cheques -->
                <div class="col-lg-6">
                    @permission(['manage-gymie','view-dashboard-expense-tab'])
                    <div class="panel">
                        <div class="panel-title">
                            <div class="panel-head"><i class="fa fa-inr"></i><a href="{{ action('ExpensesController@index') }}">المصروفات</a></div>
                            <div class="pull-right"><a href="{{ action('ExpensesController@create') }}" class="btn-sm btn-primary active" role="button">
                                    <i class="fa fa-inr"></i> إضافة</a>
                            </div>
                        </div>

                        <div class="panel-body with-nav-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#due" data-toggle="tab">مستحق</a></li>
                                <li><a href="#outstanding" data-toggle="tab">غير مسدد</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="due">
                                    @include('dashboard._index.due', ['dues' => $dues])
                                </div>
                                <div class="tab-pane fade" id="outstanding">
                                    @include('dashboard._index.outStanding', ['outstandings' => $outstandings])
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission

                    <div class="panel">
                        <div class="panel-title">
                            <div class="panel-head"><i class="fa fa-credit-card-alt" aria-hidden="true"></i>الشيكات</div>
                        </div>

                        <div class="panel-body with-nav-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#recieved" data-toggle="tab">مستلم<span
                                                class="label label-warning margin-left-5">{{ $recievedChequesCount }}</span></a></li>
                                <li><a href="#deposited" data-toggle="tab">مودع<span
                                                class="label label-primary margin-left-5">{{ $depositedChequesCount }}</span></a></li>
                                <li><a href="#bounced" data-toggle="tab">مرتجع<span class="label label-danger margin-left-5">{{ $bouncedChequesCount }}</span></a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="recieved">
                                    @include('dashboard._index.receivedCheque', ['recievedCheques' =>  $recievedCheques])
                                </div>
                                <div class="tab-pane fade" id="deposited">
                                    @include('dashboard._index.depositedCheques', ['depositedCheques' =>  $depositedCheques])
                                </div>
                                <div class="tab-pane fade" id="bounced">
                                    @include('dashboard._index.bouncedCheques', ['bouncedCheques' =>  $bouncedCheques])
                                </div>
                            </div>
                        </div>
                    </div>

                    @permission(['manage-gymie','view-dashboard-charts'])
                    <!-- SMS block -->
                    <div class="panel">
                        <div class="panel-title">
                            <div class="panel-head"><i class="fa fa-comments-o"></i>سجل الرسائل</div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel bg-white">
                                        <div class="panel-body padding-15-20">
                                            <div class="clearfix">
                                                <div class="pull-left">
                                                    <div class="color-text-primary font-size-24 font-roboto font-weight-600" data-toggle="counter" data-start="0"
                                                         data-from="0" data-to="{{ \Utilities::getSetting('sms_balance') }}" data-speed="500"
                                                         data-refresh-interval="10"></div>
                                                </div>
                                                <div class="pull-right">
                                                    <i class="font-size-24 color-primary-500 fa fa-comments"></i>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="pull-left">
                                                    <div class="display-block color-text-secondary font-weight-600">رصيد الرسائل</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($smsRequestSetting == 0)
                                    <div class="col-lg-12">
                                        <button class="btn btn-labeled btn-success pull-right margin-top-20" data-toggle="modal" data-target="#smsRequestModal"
                                                data-id="smsRequestModal"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>طلب رسائل إضافية
                                        </button>
                                    </div>
                                @endif
                            </div>
                            @include('dashboard._index.smsLog', ['smslogs' => $smslogs])
                        </div>
                    </div>
                    @endpermission
                </div>

            </div>

            <!-- SMS request confirmation Modal -->
            <div id="smsRequestModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">تأكيد طلب باقة رسائل جديدة</h4>
                        </div>
                        <div class="modal-body">
                            {!! Form::Open(['action' => 'DashboardController@smsRequest']) !!}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        {!! Form::label('smsCount','اختر باقة الرسائل') !!}
                                        {!! Form::select('smsCount',array('5000' => '5000 sms', '10000' => '10000 sms', '15000' => '15000 sms'),null,['class'=>'form-control selectpicker show-tick show-menu-arrow', 'id' => 'smsCount']) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-info" value="إرسال" id="smsRequest"/>
                            {!! Form::Close() !!}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@stop

@section('footer_scripts')
    <script src="{{ URL::asset('assets/plugins/morris/raphael-2.1.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
@stop

@section('footer_script_init')
    <script type="text/javascript">
        $(document).ready(function () {
            gymie.loadmorris();
        });
    </script>
@stop

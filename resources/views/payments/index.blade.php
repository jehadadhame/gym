@extends('app')

@section('content')

<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
        <h1 class="page-title no-line-height">المدفوعات
            @permission(['manage-gymie', 'manage-payments', 'add-payment'])
            <a href="{{ action('PaymentsController@create') }}" class="page-head-btn btn-sm btn-primary active"
                role="button">إضافة جديد</a>
            @endpermission
            <small>تفاصيل جميع مدفوعات الصالة</small>
        </h1>
        @permission(['manage-gymie', 'pagehead-stats'])
        <h1 class="font-size-30 text-right color-primary-500 animated fadeInDown total-count pull-right"><span
                data-toggle="counter" data-start="0" data-from="0" data-to="{{ $count }}" data-speed="600"
                data-refresh-interval="10"></span>
            <small class="color-text-secondary display-block margin-top-5 font-size-14">إجمالي المدفوعات (₪)</small>
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
                                {!! Form::label('member-daterangepicker', 'نطاق التاريخ', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <div id="member-daterangepicker" class="form-control gymie-daterangepicker" style="cursor: pointer; width: 100%;">
                                    <i class="ion-calendar margin-right-10 color-primary-500"></i>
                                    <span>{{$drp_placeholder}}</span>
                                    <i class="ion-ios-arrow-down margin-left-5 pull-left"></i>
                                </div>
                                {!! Form::text('drp_start', null, ['class' => 'hidden', 'id' => 'drp_start']) !!}
                                {!! Form::text('drp_end', null, ['class' => 'hidden', 'id' => 'drp_end']) !!}
                            </div>

                            <div class="col-md-2 col-sm-6 margin-bottom-10">
                                {!! Form::label('sort_field', 'ترتيب حسب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                {!! Form::select('sort_field', array('created_at' => 'التاريخ', 'payment_amount' => 'المبلغ', 'mode' => 'النوع', 'member_name' => 'اسم العضو', 'member_code' => 'كود العضو', 'invoice_number' => 'رقم الفاتورة'), old('sort_field'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_field', 'style' => 'width: 100%;']) !!}
                            </div>

                            <div class="col-md-2 col-sm-6 margin-bottom-10">
                                {!! Form::label('sort_direction', 'الترتيب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                {!! Form::select('sort_direction', array('desc' => 'تنازلي', 'asc' => 'تصاعدي'), old('sort_direction'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_direction', 'style' => 'width: 100%;']) !!}
                            </div>

                            <div class="col-md-3 col-sm-6 margin-bottom-10">
                                {!! Form::label('search', 'كلمة البحث', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <input value="{{ old('search') }}" name="search" id="search" type="text" class="form-control" placeholder="بحث..." style="width: 100%;">
                            </div>

                            <div class="col-md-2 col-sm-12 margin-bottom-10 text-left" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i> بحث</button>
                            </div>
                        </div>
                        {!! Form::Close() !!}
                    </div>

                    <div class="panel-body padding-20">
                        @if($payment_details->count() == 0)
                            <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لم يتم العثور على سجلات</h4>
                        @else
                            <div class="text-left mb-3 margin-bottom-15">
                                <button class="btn btn-success" onclick="window.print()">
                                    <i class="fa fa-print"></i> طباعة المدفوعات
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table id="payments" class="table table-bordered table-striped" dir="rtl">
                                    <thead>
                                        <tr>
                                            <th class="text-right">رقم الفاتورة</th>
                                            <th class="text-right">اسم العضو</th>
                                            <th class="text-right">المبلغ</th>
                                            <th class="text-right">النوع</th>
                                            <th class="text-right">التاريخ</th>
                                            <th class="text-center">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payment_details as $payment_detail)
                                            <?php $cheque_detail = App\ChequeDetail::where('payment_id', $payment_detail->id)->first(); ?>
                                            <tr>
                                                <td>
                                                    <a href="{{ action('InvoicesController@show',['id' => $payment_detail->invoice_id]) }}" class="font-weight-600 color-primary-500">{{ $payment_detail->invoice_number }}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ action('MembersController@show',['id' => $payment_detail->member_id]) }}" class="font-weight-600 color-text-primary">{{ $payment_detail->member_name }}</a>
                                                </td>
                                                <td class="font-weight-700">
                                                    ₪ {{ ($payment_detail->payment_amount >= 0 ? $payment_detail->payment_amount : str_replace("-", "", $payment_detail->payment_amount) . " (مدفوع)") }}
                                                </td>
                                                @if($payment_detail->mode == 1)
                                                    <td>{{ Utilities::getPaymentMode($payment_detail->mode)}}</td>
                                                @elseif($payment_detail->mode == 0)
                                                    <td>{{ Utilities::getPaymentMode($payment_detail->mode)}}
                                                        ({{ ($cheque_detail ? Utilities::getChequeStatus($cheque_detail->status) : "NA") }})
                                                    </td>
                                                @endif
                                                <td dir="ltr" class="text-right">{{ $payment_detail->created_at->format('Y-m-d H:i') }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            الإجراءات <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                            <li>
                                                                @permission(['manage-gymie', 'manage-payments', 'edit-payment'])
                                                                <a href="{{ action('PaymentsController@edit',['id' => $payment_detail->id]) }}">
                                                                    <i class="fa fa-pencil"></i> تعديل التفاصيل
                                                                </a>
                                                                @endpermission
                                                            </li>
                                                            
                                                            @if($payment_detail->mode == 0)
                                                                <?php
                                                                $cheque = App\ChequeDetail::where('payment_id', $payment_detail->id)->whereIn('status', ['0', '1', '3'])->first();
                                                                $result = ($cheque == null) ? false : true;
                                                                ?>
                                                                @if($result == true && $payment_detail->mode == 0)
                                                                    @if($cheque->status == 0)
                                                                        <li>
                                                                            <a href="{{ action('PaymentsController@depositCheque',['id' => $payment_detail->id]) }}"><i class="fa fa-bank"></i> تحديد كمودع</a>
                                                                        </li>
                                                                    @elseif($cheque->status == 1)
                                                                        <li>
                                                                            <a href="{{ action('PaymentsController@clearCheque',['id' => $payment_detail->id]) }}"><i class="fa fa-check-square-o"></i> تحديد كمسدد</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ action('PaymentsController@chequeBounce',['id' => $payment_detail->id]) }}"><i class="fa fa-times-circle"></i> تحديد كمرتجع</a>
                                                                        </li>
                                                                    @elseif($cheque->status == 3)
                                                                        <li>
                                                                            <a href="{{ action('PaymentsController@chequeReissue',['id' => $payment_detail->id]) }}"><i class="fa fa-refresh"></i> أعيد إصداره</a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                            
                                                            <li class="divider"></li>
                                                            <li>
                                                                @permission(['manage-gymie', 'manage-payments', 'delete-payment'])
                                                                <a href="#" class="delete-record color-error-text" data-delete-url="{{ url('payments/' . $payment_detail->id . '/delete') }}" data-record-id="{{$payment_detail->id}}">
                                                                    <i class="fa fa-trash"></i> حذف المعاملة
                                                                </a>
                                                                @endpermission
                                                            </li>
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
                                        عرض الصفحة {{ $payment_details->currentPage() }} من {{ $payment_details->lastPage() }}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="gymie_paging pull-left">
                                        {!! str_replace('/?', '?', $payment_details->appends(Input::Only('search'))->render()) !!}
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

<style>
    @media print {
        /* Hide unwanted columns */
        th:nth-child(1),
        td:nth-child(1), /* Invoice Number */
        th:nth-child(4),
        td:nth-child(4), /* Mode */
        th:nth-child(6),
        td:nth-child(6) { /* Actions */
            display: none;
        }

        /* Hide filters, buttons, and pagination */
        .btn,
        form,
        .panel-heading,
        .gymie_paging,
        .gymie_paging_info {
            display: none !important;
        }

        /* Show only relevant page heading and total count */
        .page-head {
            display: block !important;
            background: none !important;
            padding: 0;
            margin-bottom: 20px;
            border: none;
            text-align: right;
            direction: rtl;
        }

        .page-head h1.page-title {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .page-head .total-count {
            display: block !important;
            font-size: 18px;
            color: #000 !important;
            float: left !important;
            text-align: right;
            margin-top: 10px;
        }

        /* Clean up layout */
        body {
            background: #fff;
            direction: rtl;
        }

        .panel-body {
            background: #fff !important;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            direction: rtl;
        }

        th, td {
            border: 1px solid #ccc !important;
            padding: 6px;
            text-align: right !important;
        }
    }
</style>
@extends('app')

@section('content')
<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
        <h1 class="page-title no-line-height">الفواتير
            <small>تفاصيل جميع فواتير الصالة</small>
        </h1>
        @permission(['manage-gymie', 'pagehead-stats'])
        <h1 class="font-size-30 text-right color-primary-500 animated fadeInDown total-count pull-right"><span
                data-toggle="counter" data-start="0" data-from="0" data-to="{{ $count }}" data-speed="600"
                data-refresh-interval="10"></span>
            <small class="color-text-secondary display-block margin-top-5 font-size-14">إجمالي الفواتير</small>
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
                                {!! Form::label('invoice-daterangepicker', 'نطاق التاريخ', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <div id="invoice-daterangepicker" class="form-control gymie-daterangepicker" style="cursor: pointer; width: 100%;">
                                    <i class="ion-calendar margin-right-10 color-primary-500"></i>
                                    <span>{{$drp_placeholder}}</span>
                                    <i class="ion-ios-arrow-down margin-left-5 pull-left"></i>
                                </div>
                                {!! Form::text('drp_start', null, ['class' => 'hidden', 'id' => 'drp_start']) !!}
                                {!! Form::text('drp_end', null, ['class' => 'hidden', 'id' => 'drp_end']) !!}
                            </div>

                            <div class="col-md-3 col-sm-6 margin-bottom-10">
                                {!! Form::label('sort_field', 'ترتيب حسب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                {!! Form::select('sort_field', array('created_at' => 'التاريخ', 'invoice_number' => 'رقم الفاتورة', 'member_name' => 'اسم العضو', 'total' => 'المبلغ الإجمالي', 'pending_amount' => 'المبلغ المتبقي'), old('sort_field'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_field', 'style' => 'width: 100%;']) !!}
                            </div>

                            <div class="col-md-3 col-sm-6 margin-bottom-10">
                                {!! Form::label('sort_direction', 'الترتيب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                {!! Form::select('sort_direction', array('desc' => 'تنازلي', 'asc' => 'تصاعدي'), old('sort_direction'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_direction', 'style' => 'width: 100%;']) !!}
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
                        @if($invoices->count() == 0)
                            <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لا توجد سجلات</h4>
                        @else
                            <div class="table-responsive">
                                <table id="invoices" class="table table-bordered table-striped" dir="rtl">
                                    <thead>
                                        <tr>
                                            <th class="text-right">رقم الفاتورة</th>
                                            <th class="text-right">اسم العضو</th>
                                            <th class="text-right">المبلغ الإجمالي</th>
                                            <th class="text-right">المبلغ المتبقي</th>
                                            <th class="text-right">الخصم</th>
                                            <th class="text-right">الحالة</th>
                                            <th class="text-right">تاريخ الإنشاء</th>
                                            <th class="text-center">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $invoice)
                                            <tr>
                                                <td>
                                                    <a href="{{ action('InvoicesController@show',['id' => $invoice->id]) }}" class="font-weight-600 color-primary-500">{{ $invoice->invoice_number}}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ action('MembersController@show',['id' => $invoice->member->id]) }}" class="font-weight-600 color-text-primary">{{ $invoice->member_name}}</a>
                                                </td>
                                                <td class="font-weight-700">₪ {{ $invoice->total}}</td>
                                                <td class="font-weight-700 color-error-text">₪ {{ $invoice->pending_amount}}</td>
                                                <td>₪ {{ $invoice->discount_amount}}</td>
                                                <td>
                                                    <span class="{{ Utilities::getInvoiceLabel($invoice->status) }}">{{ Utilities::getInvoiceStatus($invoice->status) }}</span>
                                                </td>
                                                <td dir="ltr" class="text-right">{{ $invoice->created_at->format('Y-m-d H:i')}}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            الإجراءات <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                            <li>
                                                                @permission(['manage-gymie', 'manage-invoices', 'view-invoice'])
                                                                <a href="{{ action('InvoicesController@show',['id' => $invoice->id]) }}">
                                                                    <i class="fa fa-file-text-o"></i> عرض الفاتورة
                                                                </a>
                                                                @endpermission
                                                            </li>
                                                            @if($invoice->discount_amount > 0)
                                                                @permission(['manage-gymie', 'manage-invoices', 'add-discount'])
                                                                <li>
                                                                    <a href="{{ action('InvoicesController@discount',['id' => $invoice->id]) }}">
                                                                        <i class="fa fa-tag"></i> تعديل الخصم
                                                                    </a>
                                                                </li>
                                                                @endpermission
                                                            @elseif($invoice->discount_amount == 0)
                                                                @permission(['manage-gymie', 'manage-invoices', 'add-discount'])
                                                                <li>
                                                                    <a href="{{ action('InvoicesController@discount',['id' => $invoice->id]) }}">
                                                                        <i class="fa fa-tag"></i> إضافة خصم
                                                                    </a>
                                                                </li>
                                                                @endpermission
                                                            @endif
                                                            <li class="divider"></li>
                                                            <li>
                                                                @permission(['manage-gymie', 'manage-invoices', 'delete-invoice'])
                                                                <a href="#" class="delete-record color-error-text" data-delete-url="{{ url('invoices/' . $invoice->id . '/delete') }}" data-record-id="{{$invoice->id}}">
                                                                    <i class="fa fa-trash"></i> حذف الفاتورة
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
                                        عرض الصفحة {{ $invoices->currentPage() }} من {{ $invoices->lastPage() }}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="gymie_paging pull-left">
                                        {!! str_replace('/?', '?', $invoices->appends(Input::Only('search'))->render()) !!}
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
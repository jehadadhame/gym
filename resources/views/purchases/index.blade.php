@extends('app')

@section('content')

    <div class="rightside bg-grey-100">
        <!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
            @include('flash::message')
            <h1 class="page-title no-line-height">المشتريات
                @permission(['manage-gymie','manage-purchases','add-purchases'])
                <a href="{{ action('PurchaseController@create') }}" class="page-head-btn btn-sm btn-primary active" role="button">إضافة جديد</a>
                @endpermission
                <small>تفاصيل جميع المشتريات</small>
            </h1>
            @permission(['manage-gymie','pagehead-stats'])
            <h1 class="font-size-30 text-right color-primary-500 animated fadeInDown total-count pull-right"><span data-toggle="counter" data-start="0"
                                                                                                                     data-from="0" data-to="{{ $count }}"
                                                                                                                     data-speed="600"
                                                                                                                     data-refresh-interval="10"></span>
                <small class="color-text-secondary display-block margin-top-5 font-size-14">إجمالي المشتريات</small>
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
                            <div class="row" dir="rtl">
                                {!! Form::Open(['method' => 'GET', 'class' => 'form-inline']) !!}

                                <div class="col-md-3 col-sm-6 margin-bottom-10">
                                    {!! Form::label('purchases-daterangepicker','نطاق التاريخ', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                    <div id="purchases-daterangepicker"
                                         class="form-control gymie-daterangepicker" style="cursor: pointer; width: 100%;">
                                        <i class="ion-calendar margin-right-10 color-primary-500"></i>
                                        <span>{{$drp_placeholder}}</span>
                                        <i class="ion-ios-arrow-down margin-left-5 pull-left"></i>
                                    </div>
                                    {!! Form::text('drp_start',null,['class'=>'hidden', 'id' => 'drp_start']) !!}
                                    {!! Form::text('drp_end',null,['class'=>'hidden', 'id' => 'drp_end']) !!}
                                </div>

                                <div class="col-md-2 col-sm-6 margin-bottom-10">
                                    {!! Form::label('sort_field','ترتيب حسب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                    {!! Form::select('sort_field',array('created_at' => 'التاريخ','product_name' => 'اسم المنتج'),old('sort_field'),['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_field', 'style' => 'width: 100%;']) !!}
                                </div>

                                <div class="col-md-2 col-sm-6 margin-bottom-10">
                                    {!! Form::label('sort_direction','الترتيب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                    {!! Form::select('sort_direction',array('desc' => 'تنازلي','asc' => 'تصاعدي'),old('sort_direction'),['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_direction', 'style' => 'width: 100%;']) !!}
                                </div>

                                <div class="col-md-2 col-sm-6 margin-bottom-10">
                                    {!! Form::label('product_name','اسم المنتج', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                    <?php $products = App\Product::all(); ?>
                                    <select id="product_id" name="product_id" class="form-control selectpicker show-tick" style="width: 100%;">
                                        <option value="0" {{ (old('product_id') == "" ? "selected" : "") }}>الكل</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ (old('product_id') == $product->id ? "selected" : "") }}>{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 col-sm-6 margin-bottom-10">
                                    {!! Form::label('search','كلمة البحث', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                    <input value="{{ old('search') }}" name="search" id="search" type="text" class="form-control" placeholder="بحث..." style="width: 100%;">
                                </div>

                                <div class="col-md-1 col-sm-12 margin-bottom-10 text-left" style="margin-top: 25px;">
                                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i></button>
                                </div>

                                {!! Form::Close() !!}
                            </div>
                        </div>

                        <div class="panel-body padding-20">
                            @if($purchases->count() == 0)
                                <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لا توجد سجلات</h4>
                            @else
                                <div class="table-responsive">
                                    <table id="purchases" class="table table-bordered table-striped" dir="rtl">
                                        <thead>
                                        <tr>
                                            <th class="text-right">كود العضو</th>
                                            <th class="text-right">اسم العضو</th>
                                            <th class="text-right">اسم المنتج</th>
                                            <th class="text-center">الإجراءات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($purchases as $purchase)
                                            <tr>
                                                <td>
                                                    <a href="{{ action('MembersController@show',['id' => $purchase->member->id]) }}" class="font-weight-600 color-primary-500">{{ $purchase->member->member_code}}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ action('MembersController@show',['id' => $purchase->member->id]) }}" class="font-weight-600 color-text-primary">{{ $purchase->member->name}}</a>
                                                </td>
                                                <td>{{ $purchase->product_name}}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            الإجراءات <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                            <li>
                                                                @permission(['manage-gymie','manage-purchase','edit-purchase'])
                                                                <a href="{{ action('PurchaseController@edit',['id' => $purchase->id]) }}">
                                                                    <i class="fa fa-pencil"></i> تعديل التفاصيل
                                                                </a>
                                                                @endpermission
                                                            </li>
                                                            @permission(['manage-gymie','manage-purchase','change-purchase'])
                                                            <li>
                                                                <a href="{{ action('PurchaseController@change',['id' => $purchase->id]) }}">
                                                                    <i class="fa fa-exchange"></i> استبدال / تعديل
                                                                </a>
                                                            </li>
                                                            @endpermission
                                                            <li class="divider"></li>
                                                            @permission(['manage-gymie','manage-purchase','delete-purchase'])
                                                            <li>
                                                                <a href="#" class="delete-record color-error-text" data-delete-url="{{ url('purchases/'.$purchase->id.'/delete') }}"
                                                                   data-record-id="{{$purchase->id}}">
                                                                    <i class="fa fa-trash"></i> حذف عملية الشراء
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
                                            عرض الصفحة {{ $purchases->currentPage() }} من {{ $purchases->lastPage() }}
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="gymie_paging pull-left">
                                            {!! str_replace('/?', '?', $purchases->appends(Input::all())->render()) !!}
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
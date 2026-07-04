@extends('app')

@section('content')
<div class="rightside bg-grey-100">

    <!-- BEGIN PAGE HEADING -->
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
        <h1 class="page-title no-line-height">المنتجات
            @permission(['manage-gymie', 'manage-products', 'add-product'])
            <a href="{{ action('ProductController@create') }}" class="page-head-btn btn-sm btn-primary active"
                role="button">إضافة جديد</a>
            @endpermission
            <small>تفاصيل جميع المنتجات</small>
        </h1>
        @permission(['manage-gymie', 'pagehead-stats'])
        <h1 class="font-size-30 text-right color-primary-500 animated fadeInDown total-count pull-right">
            <span data-toggle="counter" data-start="0" data-from="0" data-to="{{ $count }}" data-speed="600"
                data-refresh-interval="10"></span>
            <small class="color-text-secondary display-block margin-top-5 font-size-14">إجمالي المنتجات</small>
        </h1>
        @endpermission
    </div><!-- / PageHead -->

    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel bg-white">
                    <div class="panel-heading bg-white" style="border-bottom: 1px solid var(--color-border-light); padding: 15px 20px;">
                        <div class="row" dir="rtl">
                            {!! Form::Open(['method' => 'GET', 'class' => 'form-inline']) !!}
                            
                            <div class="col-md-3 col-sm-6 margin-bottom-10 pull-right">
                                {!! Form::label('search','كلمة البحث', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <input value="{{ old('search') }}" name="search" id="search" type="text" class="form-control" placeholder="بحث..." style="width: 100%;">
                            </div>

                            <div class="col-md-1 col-sm-12 margin-bottom-10 text-left pull-right" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i></button>
                            </div>
                            
                            {!! Form::Close() !!}
                        </div>
                    </div>

                    <div class="panel-body padding-20">
                        @if($products->count() == 0)
                            <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لا توجد سجلات</h4>
                        @else

                            <div class="table-responsive">
                                <table id="products" class="table table-bordered table-striped" dir="rtl">
                                    <thead>
                                        <tr>
                                            <th class="text-right">كود المنتج</th>
                                            <th class="text-right">اسم المنتج</th>
                                            <th class="text-right">تفاصيل المنتج</th>
                                            <th class="text-right">المبلغ</th>
                                            <th class="text-right">الحالة</th>
                                            <th class="text-center">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($products as $product)
                                            <tr>
                                                <td class="font-weight-600 color-primary-500">{{ $product->product_code}}</td>
                                                <td class="font-weight-600 color-text-primary">{{ $product->product_name}}</td>
                                                <td>{{ $product->product_description}}</td>
                                                <td class="font-weight-700">₪ {{ $product->amount}}</td>
                                                <td>
                                                    <span class="{{ Utilities::getActiveInactive ($product->status) }}">{{ Utilities::getStatusValue ($product->status) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            الإجراءات <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                            <li>
                                                                @permission(['manage-gymie','manage-products','edit-product'])
                                                                <a href="{{ action('ProductController@edit',['id' => $product->id]) }}">
                                                                    <i class="fa fa-pencil"></i> تعديل التفاصيل
                                                                </a>
                                                                @endpermission
                                                            </li>
                                                            <li class="divider"></li>
                                                            <li>
                                                                @permission(['manage-gymie','manage-products','delete-product'])
                                                                <a href="#" class="delete-record color-error-text" data-delete-url="{{ url('products/'.$product->id.'/delete') }}"
                                                                    data-record-id="{{$product->id}}">
                                                                    <i class="fa fa-trash"></i> حذف المنتج
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

                            <!-- Pagination -->
                            <div class="row margin-top-20">
                                <div class="col-xs-6">
                                    <div class="gymie_paging_info color-text-secondary">
                                        عرض الصفحة {{ $products->currentPage() }} من {{ $products->lastPage() }}
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="gymie_paging pull-left">
                                        {!! str_replace('/?', '?', $products->appends(Input::Only('search'))->render()) !!}
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
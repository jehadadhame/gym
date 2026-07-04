@extends('app')

@section('content')
    <div class="rightside bg-grey-100">

        <!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
            @include('flash::message')
            <h1 class="page-title no-line-height">فئات المصروفات
                @permission(['manage-gymie','manage-expenseCategories','add-expenseCategory'])
                <a href="{{ action('ExpenseCategoriesController@create') }}" class="page-head-btn btn-sm btn-primary active" role="button">إضافة جديد</a>
                @endpermission
                <small>تفاصيل جميع فئات المصروفات</small>
            </h1>
            @permission(['manage-gymie','pagehead-stats'])
            <h1 class="font-size-30 text-right color-primary-500 animated fadeInDown total-count pull-right"><span data-toggle="counter" data-start="0"
                                                                                                                     data-from="0" data-to="{{ $count }}"
                                                                                                                     data-speed="600"
                                                                                                                     data-refresh-interval="10"></span>
                <small class="color-text-secondary display-block margin-top-5 font-size-14">إجمالي الفئات</small>
            </h1>
            @endpermission
        </div><!-- / PageHead -->

        <div class="container-fluid">
            <div class="row"><!-- Main row -->
                <div class="col-lg-12"><!-- Main col -->
                    <div class="panel bg-white">
                        <div class="panel-body padding-20">
                            @if($expenseCategories->count() == 0)
                                <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لا توجد سجلات</h4>
                            @else
                                <div class="table-responsive">
                                    <table id="expenseCategories" class="table table-bordered table-striped" dir="rtl">
                                        <thead>
                                        <tr>
                                            <th class="text-right">اسم الفئة</th>
                                            <th class="text-right">الحالة</th>
                                            <th class="text-center">الإجراءات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($expenseCategories as $expenseCategory)
                                            <tr>
                                                <td class="font-weight-600 color-text-primary">{{ $expenseCategory->name}}</td>
                                                <td><span class="{{ Utilities::getActiveInactive($expenseCategory->status) }}">{{ Utilities::getStatusValue($expenseCategory->status) }}</span></td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            الإجراءات <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                            <li>
                                                                @permission(['manage-gymie','manage-expenseCategories','edit-expenseCategory'])
                                                                <a href="{{ action('ExpenseCategoriesController@edit',['id' => $expenseCategory->id]) }}">
                                                                    <i class="fa fa-pencil"></i> تعديل الفئة
                                                                </a>
                                                                @endpermission
                                                            </li>
                                                            <li class="divider"></li>
                                                            <li>
                                                                @permission(['manage-gymie','manage-expenseCategories','delete-expenseCategory'])
                                                                <a href="#" class="delete-record color-error-text" data-delete-url="{{ url('expenseCategories/'.$expenseCategory->id.'/delete') }}"
                                                                   data-record-id="{{$expenseCategory->id}}">
                                                                    <i class="fa fa-trash"></i> حذف الفئة
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
                                            عرض الصفحة {{ $expenseCategories->currentPage() }} من {{ $expenseCategories->lastPage() }}
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="gymie_paging pull-left">
                                            {!! str_replace('/?', '?', $expenseCategories->render()) !!}
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
@extends('app')

@section('content')

    <div class="rightside bg-grey-100">
        <!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
            @include('flash::message')
            <h1 class="page-title no-line-height">المصروفات
                @permission(['manage-gymie','manage-expenses','add-expense'])
                <a href="{{ action('ExpensesController@create') }}" class="page-head-btn btn-sm btn-primary active" role="button">إضافة جديد</a>
                @endpermission
                <small>تفاصيل جميع المصروفات</small>
            </h1>
            @permission(['manage-gymie','pagehead-stats'])
            <h1 class="font-size-30 text-right color-primary-500 animated fadeInDown total-count pull-right"><span data-toggle="counter" data-start="0"
                                                                                                                     data-from="0" data-to="{{ $count }}"
                                                                                                                     data-speed="600"
                                                                                                                     data-refresh-interval="10"></span>
                <small class="color-text-secondary display-block margin-top-5 font-size-14">إجمالي المصروفات</small>
            </h1>
            @endpermission
        </div><!-- / PageHead -->

        <div class="container-fluid">
            <div class="row"><!-- Main row -->
                <div class="col-lg-12"><!-- Main col -->
                    <div class="panel bg-white">
                        <div class="panel-heading bg-white" style="border-bottom: 1px solid var(--color-border-light); padding: 15px 20px;">

                            <div class="row" dir="rtl">
                                {!! Form::Open(['method' => 'GET', 'class' => 'form-inline']) !!}

                                <div class="col-md-3 col-sm-6 margin-bottom-10">
                                    {!! Form::label('expense-daterangepicker','نطاق التاريخ', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                    <div id="expense-daterangepicker"
                                         class="form-control gymie-daterangepicker" style="cursor: pointer; width: 100%;">
                                        <i class="ion-calendar margin-right-10 color-primary-500"></i>
                                        <span>{{$drp_placeholder}}</span>
                                        <i class="ion-ios-arrow-down margin-left-5 pull-left"></i>
                                    </div>
                                    {!! Form::text('drp_start',null,['class'=>'hidden', 'id' => 'drp_start']) !!}
                                    {!! Form::text('drp_end',null,['class'=>'hidden', 'id' => 'drp_end']) !!}
                                </div>

                                <div class="col-md-2 col-sm-6 margin-bottom-10">
                                    <?php $expenseCategories = App\ExpenseCategory::where('status', '=', '1')->get(); ?>
                                    {!! Form::label('category_id','الفئة', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                    <?php $client_catid = isset($_GET['category_id']) ? $_GET['category_id'] : '0'; ?>
                                    <select id="category_id" name="category_id" class="form-control selectpicker show-tick show-menu-arrow" style="width: 100%;">
                                        <option value="0" <?php echo $client_catid == 0 ? 'selected="selected" ' : '' ?>>الكل</option>
                                        @foreach($expenseCategories as $expenseCategory)
                                            <option value="{{ $expenseCategory->id }}" <?php echo $client_catid == $expenseCategory->id ? 'selected="selected" ' : '' ?>>{{ $expenseCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 col-sm-6 margin-bottom-10">
                                    {!! Form::label('sort_field','ترتيب حسب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                    {!! Form::select('sort_field',array('created_at' => 'التاريخ','name' => 'الاسم','amount' => 'المبلغ','due_date' => 'تاريخ الدفع','category_name' => 'اسم الفئة'),old('sort_field'),['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_field', 'style' => 'width: 100%;']) !!}
                                </div>

                                <div class="col-md-2 col-sm-6 margin-bottom-10">
                                    {!! Form::label('sort_direction','الترتيب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                    {!! Form::select('sort_direction',array('desc' => 'تنازلي','asc' => 'تصاعدي'),old('sort_direction'),['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_direction', 'style' => 'width: 100%;']) !!}
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
                            @if($expenseCategories->count() == 0)
                                <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لا توجد سجلات</h4>
                            @else
                                <div class="table-responsive">
                                    <table id="expenses" class="table table-bordered table-striped" dir="rtl">
                                        <thead>
                                        <tr>
                                            <th class="text-right">اسم المصروف</th>
                                            <th class="text-right">فئة المصروف</th>
                                            <th class="text-right">المبلغ</th>
                                            <th class="text-right">التكرار</th>
                                            <th class="text-right">تاريخ الدفع</th>
                                            <th class="text-right">تاريخ الإضافة</th>
                                            <th class="text-right">الحالة</th>
                                            <th class="text-center">الإجراءات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($expenses as $expense)
                                            <tr>
                                                <td class="font-weight-600 color-text-primary">{{ $expense->name }}</td>
                                                <td>{{ $expense->category->name }}</td>
                                                <td class="font-weight-700">₪ {{ $expense->amount }}</td>
                                                <td>{{ Utilities::expenseRepeatIntervel($expense->repeat) }}</td>
                                                <td dir="ltr" class="text-right">{{ $expense->due_date->format('Y-m-d') }}</td>
                                                <td dir="ltr" class="text-right">{{ $expense->created_at->format('Y-m-d H:i') }}</td>
                                                <td><span class="{{ Utilities::getPaidUnpaid($expense->paid) }}">{{ Utilities::getInvoiceStatus($expense->paid) }}</span></td>
                                                <td class="text-center">
                                                    @permission(['manage-gymie','manage-expenses','edit-expense'])
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            الإجراءات <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                            <li>
                                                                @if($expense->paid == 0)
                                                                    <a href="{{ action('ExpensesController@paid',['id' => $expense->id]) }}">
                                                                        <i class="fa fa-check"></i> تحديد كمدفوع
                                                                    </a>
                                                                @endif
                                                            </li>
                                                            @permission(['manage-gymie','manage-expenses','edit-expense'])
                                                            <li>
                                                                <a href="{{ action('ExpensesController@edit',['id' => $expense->id]) }}">
                                                                    <i class="fa fa-pencil"></i> تعديل المصروف
                                                                </a>
                                                            </li>
                                                            @endpermission
                                                            <li class="divider"></li>
                                                            @permission(['manage-gymie','manage-expenses','delete-expense'])
                                                            <li>
                                                                <a href="#" class="delete-record color-error-text" data-delete-url="{{ url('expenses/'.$expense->id.'/delete') }}"
                                                                   data-record-id="{{$expense->id}}">
                                                                    <i class="fa fa-trash"></i> حذف المصروف
                                                                </a>
                                                            </li>
                                                            @endpermission
                                                        </ul>
                                                    </div>
                                                    @endpermission
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row margin-top-20">
                                    <div class="col-xs-6">
                                        <div class="gymie_paging_info color-text-secondary">
                                            عرض الصفحة {{ $expenses->currentPage() }} من {{ $expenses->lastPage() }}
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="gymie_paging pull-left">
                                            {!! str_replace('/?', '?', $expenses->appends(Input::Only('search'))->render()) !!}
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
@extends('app')

@section('content')

    <div class="rightside bg-grey-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel bg-white">
                        <div class="panel-heading bg-white" style="border-bottom: 1px solid var(--color-border-light); padding: 15px 20px;">
                            <div class="panel-title font-size-20 color-text-primary font-weight-700">أدخل تفاصيل المصروف</div>
                        </div>
                        <div class="panel-body padding-20" dir="rtl">
                            {!! Form::model($expense, ['method' => 'POST','action' => ['ExpensesController@update',$expense->id], 'id' => 'expensesform']) !!}

                            @include('expenses.form',['submitButtonText' => 'تحديث'])

                            {!! Form::Close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('footer_scripts')
    <script src="{{ URL::asset('assets/js/expense.js') }}" type="text/javascript"></script>
@stop
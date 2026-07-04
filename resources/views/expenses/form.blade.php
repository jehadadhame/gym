<?php use Carbon\Carbon; ?>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('name','اسم المصروف') !!}
            {!! Form::text('name',null,['class'=>'form-control','id'=>'name']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <?php $expenseCategories = App\ExpenseCategory::where('status', '=', '1')->lists('name', 'id'); ?>
            {!! Form::label('category_id','الفئة') !!}
            {!! Form::select('category_id',$expenseCategories,null,['class'=>'form-control selectpicker show-tick show-menu-arrow','id'=>'category_id','data-live-search'=> 'true']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('due_date','تاريخ الدفع / الاستحقاق') !!}
            {!! Form::text('due_date',(isset($expense->due_date) ? $expense->due_date->format('Y-m-d') : Carbon::today()->format('Y-m-d')),['class'=>'form-control datepicker-default','id'=>'due_date']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
        {!! Form::label('repeat','التكرار') !!}
        <!--0 for inactive , 1 for active-->
            {!! Form::select('repeat',array('0' => 'لا يتكرر', '1' => 'يومياً', '2' => 'أسبوعياً', '3' => 'شهرياً', '4' => 'سنوياً'),null,['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'repeat']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('note','ملاحظة') !!}
            {!! Form::text('note',null,['class'=>'form-control','id'=>'note']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('amount','المبلغ') !!}
            <div class="input-group">
                <div class="input-group-addon font-weight-700">₪</div>
                {!! Form::text('amount',null,['class'=>'form-control','id'=>'amount']) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary pull-left']) !!}
        </div>
    </div>
</div>
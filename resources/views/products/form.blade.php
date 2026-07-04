<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('product_code','كود المنتج') !!}
            {!! Form::text('product_code',null,['class'=>'form-control', 'id' => 'product_code']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('product_name','اسم المنتج') !!}
            {!! Form::text('product_name',null,['class'=>'form-control', 'id' => 'product_name']) !!}
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('product_details','تفاصيل المنتج') !!}
            {!! Form::text('product_details',null,['class'=>'form-control', 'id' => 'product_details']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('amount','المبلغ (بدون ضرائب)') !!}
            <div class="input-group">
                <div class="input-group-addon font-weight-700">₪</div>
                {!! Form::text('amount',null,['class'=>'form-control', 'id' => 'amount']) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
        {!! Form::label('status','الحالة') !!}
        <!--0 for inactive , 1 for active-->
            {!! Form::select('status',array('1' => 'نشط', '0' => 'غير نشط'),null,['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'status']) !!}
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
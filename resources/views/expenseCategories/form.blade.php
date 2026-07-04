<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('name','اسم الفئة') !!}
            {!! Form::text('name',null,['class'=>'form-control', 'id' => 'name']) !!}
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
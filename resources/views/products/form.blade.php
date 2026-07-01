<div class="panel-body">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('product_code','Product Code') !!}
                {!! Form::text('product_code',null,['class'=>'form-control', 'id' => 'product_code']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('product_name','Product Name') !!}
                {!! Form::text('product_name',null,['class'=>'form-control', 'id' => 'product_name']) !!}
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('product_details','Product Details') !!}
                {!! Form::text('product_details',null,['class'=>'form-control', 'id' => 'product_details']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('amount','Amount (without taxes)') !!}
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-inr"></i></div>
                    {!! Form::text('amount',null,['class'=>'form-control', 'id' => 'amount']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
            {!! Form::label('status','Status') !!}
            <!--0 for inactive , 1 for active-->
                {!! Form::select('status',array('1' => 'Active', '0' => 'InActive'),null,['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'status']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary pull-right']) !!}
            </div>
        </div>
    </div>
</div>
                            
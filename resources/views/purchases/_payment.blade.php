<div class="row">
    <div class="col-md-12">
        <div class="panel no-border">
            <div class="panel-heading">
                <div class="panel-title font-size-18 color-text-primary font-weight-700">تفاصيل الدفع</div>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('payment_amount','المبلغ المستلم') !!}
                            <div class="input-group">
                                <div class="input-group-addon font-weight-700">₪</div>
                                {!! Form::text('payment_amount',null,['class'=>'form-control', 'id' => 'payment_amount', 'data-amounttotal' => '0']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('payment_amount_pending','المبلغ المتبقي') !!}
                            <div class="input-group">
                                <div class="input-group-addon font-weight-700">₪</div>
                                {!! Form::text('payment_amount_pending',null,['class'=>'form-control', 'id' => 'payment_amount_pending', 'readonly']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('mode','طريقة الدفع') !!}
                            {!! Form::select('mode',array('1' => 'نقدي', '0' => 'شيك'),1,['class'=>'form-control selectpicker show-tick show-menu-arrow', 'id' => 'mode']) !!}
                        </div>
                    </div>

                    <div id="chequeDetails">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('number','رقم الشيك') !!}
                                {!! Form::text('number',null,['class'=>'form-control', 'id' => 'number']) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('date','تاريخ الشيك') !!}
                                {!! Form::text('date',null,['class'=>'form-control datepicker-default', 'id' => 'date']) !!}
                            </div>
                        </div>
                    </div>
                </div> <!-- /Row -->

            </div> <!-- /Box-body -->

        </div> <!-- /Box -->
    </div> <!-- /Main Column -->
</div> <!-- /Main Row -->

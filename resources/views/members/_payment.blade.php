<div class="row">
    <div class="col-md-12">
        <h4 class="form-section-title margin-top-40">تفاصيل الدفع</h4>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('payment_amount','المبلغ المستلم') !!}
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-inr"></i></div>
                                {!! Form::text('payment_amount',null,['class'=>'form-control', 'id' => 'payment_amount', 'data-amounttotal' => '0']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('payment_amount_pending','المبلغ المتبقي') !!}
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-inr"></i></div>
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
                </div> <!-- /Row -->

                <div class="row" id="chequeDetails">
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

    </div> <!-- /Main Column -->
</div> <!-- /Main Row -->


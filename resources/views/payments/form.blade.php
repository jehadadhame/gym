<div class="panel-body padding-20" dir="rtl">
    <div class="row">
        <div class="col-sm-6 pull-right">
            <div class="form-group">
                <?php  $invoiceList = App\Invoice::lists('invoice_number', 'id'); ?>
                {!! Form::label('invoice_id','رقم الفاتورة', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5 text-right']) !!}
                {!! Form::select('invoice_id',$invoiceList,(isset($invoice) ? $invoice->id : null),['class'=>'form-control selectpicker show-tick show-menu-arrow', 'id' => 'invoice_id', 'data-live-search'=> 'true']) !!}
            </div>
        </div>
        <div class="col-sm-6 pull-right">
            <div class="form-group">
                {!! Form::label('payment_amount','المبلغ', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5 text-right']) !!}
                <div class="input-group" dir="ltr">
                    {!! Form::text('payment_amount',(isset($invoice) ? $invoice->pending_amount : null),['class'=>'form-control text-right', 'id' => 'payment_amount']) !!}
                    <div class="input-group-addon font-weight-600">₪</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 pull-right">
            <div class="form-group">
                {!! Form::label('mode','طريقة الدفع', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5 text-right']) !!}
                {!! Form::select('mode',array('1' => 'نقدي', '0' => 'شيك'),(isset($payment_detail) ? $payment_detail->mode : null),['class'=>'form-control selectpicker show-tick show-menu-arrow', 'id' => 'mode']) !!}
            </div>
        </div>
    </div>

    <div id="chequeDetails" style="display:none; background-color: var(--color-surface); padding: 20px; border-radius: var(--radius-md); margin-top: 15px; margin-bottom: 20px;">
        <h4 class="form-section-title margin-bottom-20">تفاصيل الشيك</h4>
        <div class="row">
            <div class="col-sm-6 pull-right">
                <div class="form-group">
                    {!! Form::label('number','رقم الشيك', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5 text-right']) !!}
                    {!! Form::text('number',(isset($cheque_detail) ? $cheque_detail->number : null),['class'=>'form-control text-right', 'id' => 'number']) !!}
                </div>
            </div>
            <div class="col-sm-6 pull-right">
                <div class="form-group">
                    {!! Form::label('date','تاريخ الشيك', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5 text-right']) !!}
                    {!! Form::text('date',(isset($cheque_detail) ? $cheque_detail->date : null),['class'=>'form-control text-right datepicker-default', 'id' => 'date']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row margin-top-30">
        <div class="col-sm-12">
            <hr>
            <div class="form-group text-left">
                {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary btn-lg']) !!}
            </div>
        </div>
    </div>
</div>

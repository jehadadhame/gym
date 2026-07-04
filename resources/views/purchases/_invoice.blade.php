<div class="row">
    <div class="col-md-12">
        <div class="panel no-border">
            <div class="panel-heading">
                <div class="panel-title font-size-18 color-text-primary font-weight-700">تفاصيل الفاتورة</div>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('invoice_number','رقم الفاتورة') !!}
                            {!! Form::text('invoice_number',$invoice_number,['class'=>'form-control', 'id' => 'invoice_number', ($invoice_number_mode == \constNumberingMode::Auto ? 'readonly' : '')]) !!}
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('purchase_amount','رسوم المنتجات') !!}
                            <div class="input-group">
                                <div class="input-group-addon font-weight-700">₪</div>
                                {!! Form::text('purchase_amount',null,['class'=>'form-control', 'id' => 'purchase_amount','readonly' => 'readonly']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('taxes_amount',sprintf('الضريبة @ %s %%',Utilities::getSetting('taxes'))) !!}
                            <div class="input-group">
                                <div class="input-group-addon font-weight-700">₪</div>
                                {!! Form::text('taxes_amount',0,['class'=>'form-control', 'id' => 'taxes_amount','readonly' => 'readonly']) !!}
                            </div>
                        </div>
                    </div>
                </div> <!-- /Row -->

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('discount_percent','الخصم') !!}
                            <?php
                            $discounts = explode(",", str_replace(" ", "", (Utilities::getSetting('discounts'))));
                            $discounts_list = array_combine($discounts, $discounts);
                            ?>
                            <select id="discount_percent" name="discount_percent" class="form-control selectpicker show-tick show-menu-arrow">
                                <option value="0">لا يوجد</option>
                                @foreach($discounts_list as $list)
                                    <option value="{{ $list }}">{{ $list.'%' }}</option>
                                @endforeach
                                <option value="custom">مخصص (₪)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('discount_amount','قيمة الخصم') !!}
                            <div class="input-group">
                                <div class="input-group-addon font-weight-700">₪</div>
                                {!! Form::text('discount_amount',null,['class'=>'form-control', 'id' => 'discount_amount','readonly' => 'readonly']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('discount_note','ملاحظات الخصم') !!}
                            {!! Form::text('discount_note',null,['class'=>'form-control', 'id' => 'discount_note']) !!}
                        </div>
                    </div>
                </div>

            </div> <!-- /Box-body -->

        </div> <!-- /Box -->
    </div> <!-- /Main Column -->
</div> <!-- /Main Row -->
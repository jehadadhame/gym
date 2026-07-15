@extends('app')

@section('content')

    <div class="rightside bg-grey-100">
        <div class="container-fluid">
            <!-- Error Log -->
            @if ($errors->any())
                <div class="alert alert-danger" dir="rtl">
                    <button type="button" class="close pull-left" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>عذراً!</strong> واجهنا بعض المشاكل في إدخالك.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="panel bg-white">
                        <div class="panel-heading bg-white" style="border-bottom: 1px solid var(--color-border-light); padding: 15px 20px;">
                            <div class="panel-title font-size-20 color-text-primary font-weight-700">تعديل تفاصيل الشراء</div>
                        </div>

                        {!! Form::model($purchase, ['method' => 'POST','action' => ['PurchaseController@update',$purchase->id],'id'=>'purchasesform']) !!}
                        <div class="panel-body padding-20" dir="rtl">

                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <?php $member_code = App\Member::where('status', '=', '1')->lists('member_code', 'id'); ?>
                                        {!! Form::label('member_id','كود العضو') !!}

                                        {!! Form::text('member_display', $purchase->member->member_code,['class'=> 'form-control', 'id' => 'member_display','readonly' => 'readonly']) !!}
                                        {!! Form::hidden('member_id', $purchase->member_id) !!}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <?php $products = App\Product::where('status', '=', '1')->get(); ?>
                                        {!! Form::label('product_id','اسم المنتج') !!}
                                        {!! Form::text('product_display', $purchase->product->product_name,['class'=> 'form-control product-data', 'id' => 'product_display','readonly' => 'readonly','data-days' => $purchase->product->days]) !!}
                                        {!! Form::hidden('product_id', $purchase->product_id) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-2 pull-left">
                                    <div class="form-group">
                                        {!! Form::submit('تحديث', ['class' => 'btn btn-primary pull-left']) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                        {!! Form::Close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop
@section('footer_scripts')
    <script src="{{ URL::asset('assets/js/purchase.js') }}" type="text/javascript"></script>
@stop
@section('footer_script_init')
    <script type="text/javascript">
        $(document).ready(function () {
            gymie.loaddatepickerend();
        });
    </script>
@stop

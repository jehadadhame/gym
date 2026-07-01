@extends('app')

@section('content')

    <div class="rightside bg-grey-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel no-border">
                        <div class="panel-title">
                            <div class="panel-head font-size-20">Enter details of the purchase</div>
                        </div>

                        {!! Form::model($purchase, ['method' => 'POST','action' => ['PurchaseController@update',$purchase->id],'id'=>'purchasesform']) !!}
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <?php $member_code = App\Member::where('status', '=', '1')->lists('member_code', 'id'); ?>
                                        {!! Form::label('member_id','Member Code') !!}

                                        {!! Form::text('member_display', $purchase->member->member_code,['class'=> 'form-control', 'id' => 'member_display','readonly' => 'readonly']) !!}
                                        {!! Form::hidden('member_id', $purchase->member_id) !!}

                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <?php $products = App\Product::where('status', '=', '1')->get(); ?>
                                        {!! Form::label('product_id','Product Name') !!}
                                        {!! Form::text('product_display', $purchase->product->product_name,['class'=> 'form-control product-data', 'id' => 'product_display','readonly' => 'readonly','data-days' => $purchase->product->days]) !!}
                                        {!! Form::hidden('product_id', $purchase->product_id) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2 pull-right">
                    <div class="form-group">
                        {!! Form::submit('Update', ['class' => 'btn btn-primary pull-right']) !!}
                    </div>
                </div>
            </div>

            {!! Form::Close() !!}


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


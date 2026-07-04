@extends('app')

@section('content')
<div class="rightside bg-grey-100">
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel bg-white">
                    <div class="panel-title">
                        <div class="panel-head font-size-20">تعديل بيانات الدفع</div>
                    </div>

                    {!! Form::model($payment_detail, ['method' => 'POST','action' => ['PaymentsController@update',$payment_detail->id],'id' => 'paymentsform']) !!}
                    
                    @include('payments.form',['submitButtonText' => 'تحديث الدفع'])

                    {!! Form::Close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer_scripts')
    <script src="{{ URL::asset('assets/js/payment.js') }}" type="text/javascript"></script>
@stop

@section('footer_script_init')
    <script type="text/javascript">
        $(document).ready(function () {
            gymie.loaddatepicker();
            gymie.chequedetails();
        });
    </script>
@stop
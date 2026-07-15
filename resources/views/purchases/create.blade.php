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
                            <div class="panel-title font-size-20 color-text-primary font-weight-700">تفاصيل الشراء</div>
                        </div>
                        <div class="panel-body padding-20" dir="rtl">
                            {!! Form::Open(['url' => 'purchases','id'=>'purchasesform']) !!}
                            {!! Form::hidden('invoiceCounter',$invoiceCounter) !!}

                            @include('purchases.form')

                            @if(Request::is('purchases/create'))
                                @include('purchases._invoice')

                                @include('purchases._payment')

                                <div class="row">
                                    <div class="col-sm-2 pull-left">
                                        <div class="form-group">
                                            {!! Form::submit('إضافة', ['class' => 'btn btn-primary pull-left']) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {!! Form::Close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('footer_scripts')
    <script src="{{ URL::asset('assets/js/purchases.js') }}" type="text/javascript"></script>
@stop
@section('footer_script_init')
    <script type="text/javascript">
        $(document).ready(function () {
            gymie.chequedetails();
            gymie.purchases();
        });
    </script>
@stop
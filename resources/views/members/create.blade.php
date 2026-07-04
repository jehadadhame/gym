@extends('app')

@section('content')

    <div class="rightside bg-grey-100">
        <div class="container-fluid">

            <!-- Error Log -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>عذراً!</strong> يرجى التحقق من المدخلات.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::Open(['url' => 'members','id'=>'membersform','files'=>'true']) !!}

            <div class="row">
                <div class="col-md-12">
                    <div class="panel bg-white">
                        <div class="panel-body padding-20">
                            
                            <!-- تفاصيل العضو -->
                            <h4 class="form-section-title">تفاصيل العضو</h4>
                            @include('members.form')

                            @if(Request::is('members/create'))
                                <!-- Subscription Details -->
                                @include('members._subscription')

                                <!-- Invoice Details -->
                                @include('members._invoice')

                                <!-- Payment Details -->
                                @include('members._payment')

                                <!-- Submit Button Row -->
                                <div class="row margin-top-20">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            {!! Form::submit('إضافة العضو', ['class' => 'btn btn-primary pull-left']) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            {!! Form::Close() !!}

        </div> <!-- content -->
    </div> <!-- rightside -->

@stop

@section('footer_scripts')
    <script src="{{ URL::asset('assets/js/member.js') }}" type="text/javascript"></script>
@stop

@section('footer_script_init')
    <script type="text/javascript">
        $(document).ready(function () {
            gymie.loaddatepickerstart();
            gymie.chequedetails();
            gymie.subscription();
        });
    </script>
@stop

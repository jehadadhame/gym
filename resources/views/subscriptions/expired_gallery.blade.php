@extends('app')

@section('content')

<style>
    .gallery-item {
        margin-bottom: 30px;
    }

    .gallery-item img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .gallery-item-details {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
    }

    .gallery-item-details h5 {
        font-size: 18px;
        font-weight: bold;
    }

    .gallery-item-details p {
        margin-bottom: 5px;
    }
</style>
<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
        <h1 class="page-title no-line-height">Expired subscriptions
            <small>Details of all expired subscriptions</small>
        </h1>
        @permission(['manage-gymie', 'pagehead-stats'])
        <h1 class="font-size-30 text-right color-blue-grey-600 animated fadeInDown total-count pull-right">
            <span data-toggle="counter" data-start="0" data-from="0" data-to="{{ $count }}" data-speed="600"
                data-refresh-interval="10"></span>
            <small class="color-blue-grey-600 display-block margin-top-5 font-size-14">Expired Subscriptions</small>
        </h1>
        @endpermission
    </div><!-- / PageHead -->

    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel no-border ">

                    <div class="panel-title bg-blue-grey-50">
                        <div class="panel-head font-size-15">

                            <div class="row">
                                <div class="col-sm-12 no-padding">
                                    {!! Form::Open(['method' => 'GET']) !!}

                                    <div class="col-sm-3">
                                        {!! Form::label('subscription-daterangepicker', 'Date range') !!}
                                        <div id="subscription-daterangepicker"
                                            class="gymie-daterangepicker btn bg-grey-50 daterange-padding no-border color-grey-600 hidden-xs no-shadow">
                                            <i class="ion-calendar margin-right-10"></i>
                                            <span>{{$drp_placeholder}}</span>
                                            <i class="ion-ios-arrow-down margin-left-5"></i>
                                        </div>
                                        {!! Form::text('drp_start', null, ['class' => 'hidden', 'id' => 'drp_start']) !!}
                                        {!! Form::text('drp_end', null, ['class' => 'hidden', 'id' => 'drp_end']) !!}
                                    </div>

                                    <div class="col-sm-2">
                                        {!! Form::label('sort_field', 'Sort By') !!}
                                        {!! Form::select('sort_field', array('created_at' => 'Date', 'plan_name' => 'Plan name', 'DOB' => 'Member age'), old('sort_field'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_field']) !!}
                                    </div>

                                    <div class="col-sm-2">
                                        {!! Form::label('sort_direction', 'Order') !!}
                                        {!! Form::select('sort_direction', array('desc' => 'Descending', 'asc' => 'Ascending'), old('sort_direction'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_direction']) !!}
                                    </div>

                                    <div class="col-xs-3">
                                        {!! Form::label('search', 'Keyword') !!}
                                        <input value="{{ old('search') }}" name="search" id="search" type="text"
                                            class="form-control padding-right-35" placeholder="Search...">
                                    </div>

                                    <div class="col-xs-2">
                                        {!! Form::label('&nbsp;') !!} <br />
                                        <button type="submit" class="btn btn-primary active no-border">GO</button>
                                    </div>

                                    {!! Form::Close() !!}
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="panel-body no-padding-top bg-white">
                        @if($allExpired->count() == 0)
                            <h4 class="text-center padding-top-15">Sorry! No records found</h4>
                        @else
                            <div class="row">
                                @foreach ($allExpired as $expired)
                                                        <?php
                                    $images = $expired->member->getMedia('profile');
                                    $profileImage = ($images->isEmpty() ? 'https://placeholdit.imgix.net/~text?txtsize=18&txt=NA&w=150&h=150' : url($images[0]->getUrl()));
                                                                                                                                                                                                                                                                                                                                                    ?>
                                                        <div class="col-sm-4">
                                                            <div class="gallery-item">
                                                                <a href="{{ action('MembersController@show',['id' => $expired->member->id]) }}">
                                                                    <img src="{{ $profileImage }}" class="img-fluid rounded-circle"
                                                                        alt="{{ $expired->member->name }}">
                                                                </a>
                                                                <div class="gallery-item-details">
                                                                    <h5 class="text-center">{{ $expired->member->name }}</h5>
                                                                    <p class="text-center"><strong>Plan:</strong> {{ $expired->plan->plan_name }}
                                                                    </p>
                                                                    <p class="text-center"><strong>Member Code:</strong>
                                                                        {{ $expired->member->member_code }}</p>
                                                                    <p class="text-center"><strong>Start Date:</strong>
                                                                        {{ $expired->start_date->format('Y-m-d') }}</p>
                                                                    <p class="text-center"><strong>End Date:</strong>
                                                                        {{ $expired->end_date->format('Y-m-d') }}</p>
                                                                    <div class="text-center">
                                                                        {!! Form::Open(['method' => 'POST', 'action' => ['SubscriptionsController@cancelSubscription', $expired->id]]) !!}
                                                                        <button type="submit" class="btn btn-danger">Cancel Subscription</button>
                                                                        {!! Form::Close() !!}
                                                                    </div>
                                                                    {{-- &text&type=phone_number&app_absent=0&utm_campaign=wa_api_send_v1 --}}
                                                                    <div class="text-center" style="margin: 15px 0;">
                                                                        <a href="https://web.whatsapp.com/send/?phone=%2B972{{ $expired->member->contact }}&text=مرحبا {{ $expired->member->name }}، يسعد مساك %0A اشتراكك انتهى قبل كم يوم بتحب أجدده؟"
                                                                            style="display: inline-block; margin: 10px auto; padding: 10px 20px; color: #fff; background-color: #25D366; border-radius: 5px; text-decoration: none;">
                                                                            Contact on WhatsApp (+972)
                                                                        </a>
                                                                    </div>

                                                                    <div class="text-center" style="margin: 15px 0;">
                                                                        <a href="https://web.whatsapp.com/send/?phone=%2B970{{ $expired->member->contact }}&text=مرحبا {{ $expired->member->name }}، يسعد مساك"
                                                                            style="display: inline-block; margin: 10px auto; padding: 10px 20px; color: #fff; background-color: #25D366; border-radius: 5px; text-decoration: none;">
                                                                            Contact on WhatsApp (+970)
                                                                        </a>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                @endforeach
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="gymie_paging_info">
                                        Showing page {{ $allExpired->currentPage() }} of {{ $allExpired->lastPage() }}
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="gymie_paging pull-right">
                                        {!! str_replace('/?', '?', $allExpired->appends(Input::Only('search'))->render()) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('footer_script_init')
<script type="text/javascript">
    $(document).ready(function () {
        gymie.deleterecord();
    });
</script>
@stop
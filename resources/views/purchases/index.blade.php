@extends('app')

@section('content')


    <div class="rightside bg-grey-100">
        <!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
            @include('flash::message')
            <h1 class="page-title no-line-height">Purchases
                @permission(['manage-gymie','manage-purchases','add-purchases'])
                <a href="{{ action('PurchaseController@create') }}" class="page-head-btn btn-sm btn-primary active" role="button">Add New</a>
                <small>Details of all gym purchases</small>
            </h1>
            @permission(['manage-gymie','pagehead-stats'])
            <h1 class="font-size-30 text-right color-blue-grey-600 animated fadeInDown total-count pull-right"><span data-toggle="counter" data-start="0"
                                                                                                                     data-from="0" data-to="{{ $count }}"
                                                                                                                     data-speed="600"
                                                                                                                     data-refresh-interval="10"></span>
                <small class="color-blue-grey-600 display-block margin-top-5 font-size-14">Total Purchases</small>
            </h1>
            @endpermission
            @endpermission
        </div><!-- / PageHead -->

        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel no-border ">

                        <div class="panel-title bg-blue-grey-50">
                            <div class="panel-head font-size-15">

                                <div class="row">
                                    <div class="col-sm-12 no-padding">
                                        {!! Form::Open(['method' => 'GET']) !!}

                                        <div class="col-sm-3">

                                            {!! Form::label('purchases-daterangepicker','Date range') !!}

                                            <div id="purchases-daterangepicker"
                                                 class="gymie-daterangepicker btn bg-grey-50 daterange-padding no-border color-grey-600 hidden-xs no-shadow">
                                                <i class="ion-calendar margin-right-10"></i>
                                                <span>{{$drp_placeholder}}</span>
                                                <i class="ion-ios-arrow-down margin-left-5"></i>
                                            </div>

                                            {!! Form::text('drp_start',null,['class'=>'hidden', 'id' => 'drp_start']) !!}
                                            {!! Form::text('drp_end',null,['class'=>'hidden', 'id' => 'drp_end']) !!}
                                        </div>

                                        <div class="col-sm-2">
                                            {!! Form::label('sort_field','Sort By') !!}
                                            {!! Form::select('sort_field',array('created_at' => 'Date','product_name' => 'Product name'),old('sort_field'),['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_field']) !!}
                                        </div>

                                        <div class="col-sm-2">
                                            {!! Form::label('sort_direction','Order') !!}
                                            {!! Form::select('sort_direction',array('desc' => 'Descending','asc' => 'Ascending'),old('sort_direction'),['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_direction']) !!}</span>
                                        </div>

                                        <div class="col-xs-2">
                                            {!! Form::label('product_name','Product name') !!}

                                            <?php $products = App\Product::all(); ?>

                                            <select id="product_id" name="product_id" class="form-control selectpicker show-tick">

                                                <option value="0" {{ (old('product_id') == "" ? "selected" : "") }}>All</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ (old('product_id') == $product->id ? "selected" : "") }}>{{ $product->product_name }}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="col-xs-2">
                                            {!! Form::label('search','Keyword') !!}
                                            <input value="{{ old('search') }}" name="search" id="search" type="text" class="form-control padding-right-35"
                                                   placeholder="Search...">
                                        </div>

                                        <div class="col-xs-1">
                                            {!! Form::label('&nbsp;') !!} <br/>
                                            <button type="submit" class="btn btn-primary active no-border">GO</button>
                                        </div>

                                        {!! Form::Close() !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel-body bg-white">
                            @if($purchases->count() == 0)
                                <h4 class="text-center padding-top-15">Sorry! No records found</h4>
                            @else
                                <table id="purchases" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Member Code</th>
                                        <th>Member Name</th>
                                        <th>Product Name</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>

                                        @foreach ($purchases as $purchase)

                                            <td>
                                                <a href="{{ action('MembersController@show',['id' => $purchase->member->id]) }}">{{ $purchase->member->member_code}}</a>
                                            </td>
                                            <td>
                                                <a href="{{ action('MembersController@show',['id' => $purchase->member->id]) }}">{{ $purchase->member->name}}</a>
                                            </td>
                                            <td>{{ $purchase->product_name}}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info">Actions</button>
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li>
                                                            @permission(['manage-gymie','manage-purchase','edit-purchase'])
                                                            <a href="{{ action('PurchaseController@edit',['id' => $purchase->id]) }}">
                                                                Edit details
                                                            </a>
                                                            @endpermission
                                                        </li>
                                                        @permission(['manage-gymie','manage-purchase','change-purchase'])
                                                        <li>
                                                            <a href="{{ action('PurchaseController@change',['id' => $purchase->id]) }}">
                                                                Upgrade/Downgrade
                                                            </a>
                                                        <li>
                                                            @endpermission
                                                            @permission(['manage-gymie','manage-purchase','delete-purchase'])
                                                            <a href="#" class="delete-record"
                                                               data-delete-url="{{ url('purchases/'.$purchase->id.'/delete') }}"
                                                               data-record-id="{{$purchase->id}}">
                                                                Delete purchase
                                                            </a>
                                                            @endpermission
                                                        </li>
                                                    </ul>
                                                </div>

                                            </td>

                                            </td>
                                    </tr>

                                    @endforeach

                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="gymie_paging_info">
                                            <!-- TO DO -->
                                            Showing page {{ $purchases->currentPage() }} of {{ $purchases->lastPage() }}
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="gymie_paging pull-right">

                                            {!! str_replace('/?', '?', $purchases->appends(Input::all())->render()) !!}
                                        </div>
                                    </div>
                                </div>

                        </div>
                        @endif
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
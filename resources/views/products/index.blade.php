@extends('app')

@section('content')
<div class="rightside bg-grey-100">

    <!-- BEGIN PAGE HEADING -->
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
        <h1 class="page-title no-line-height">Products
            @permission(['manage-gymie', 'manage-products', 'add-product'])
            <a href="{{ action('ProductController@create') }}" class="page-head-btn btn-sm btn-primary active"
                role="button">Add New</a>
            <small>Details of all gym Product</small>
        </h1>
        @permission(['manage-gymie', 'pagehead-stats'])
        <h1 class="font-size-30 text-right color-blue-grey-600 animated fadeInDown total-count pull-right">
            <span data-toggle="counter" data-start="0" data-from="0" data-to="{{ $count }}" data-speed="600"
                data-refresh-interval="10"></span>
            <small class="color-blue-grey-600 display-block margin-top-5 font-size-14">Total Product</small>
        </h1>
        @endpermission
        @endpermission
    </div><!-- / PageHead -->

    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel no-border ">
                    <div class="panel-body no-padding-top bg-white">
                        <div class="row margin-top-15 margin-bottom-15">
                            <div class="col-xs-12 col-md-3 pull-right">
                                {!! Form::Open(['method' => 'GET']) !!}
                                <div class="btn-inline pull-right">
                                    <input name="search" id="search" type="text" class="form-control padding-right-35"
                                        placeholder="Search...">
                                    <button
                                        class="btn btn-link no-shadow bg-transparent no-padding-top padding-right-10"
                                        type="button">
                                        <i class="ion-search"></i></button>
                                </div>
                                {!! Form::Close() !!}

                            </div>
                        </div>

                        @if($products->count() == 0)
                            <h4 class="text-center padding-top-15">Sorry! No records found</h4>
                        @else

                                <table id="products" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>product Code</th>
                                            <th>product Name</th>
                                            <th>product Details</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                                                    <tr>
                                                                        <td>{{ $product->product_code}}</td>
                                                                        <td>{{ $product->product_name}}</td>
                                                                        <td>{{ $product->product_details}}</td>
                                                                        <td>{{ $product->amount}}</td>
                                                                        <td>
                                                                            <span
                                                                                class="{{ Utilities::getActiveInactive($product->status) }}">{{ Utilities::getStatusValue($product->status) }}</span>
                                                                        </td>

                                                                        <td class="text-center">
                                                                            <div class="btn-group">
                                                                                <button type="button" class="btn btn-info">Actions</button>
                                                                                <button type="button" class="btn btn-info dropdown-toggle"
                                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                                    <span class="caret"></span>
                                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                                </button>
                                                                                <ul class="dropdown-menu" role="menu">
                                                                                    <li>
                                                                                        @permission(['manage-gymie', 'manage-products', 'edit-product'])
                                                                                        <a
                                                                                            href="{{ action('ProductController@edit',['id' => $product->id]) }}">
                                                                                            Edit details
                                                                                        </a>
                                                                                        @endpermission
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php
                                            $dependency = ($product->purchases->isEmpty() ? "false" : "true");
                                                                                                ?>
                                                                                        @permission(['manage-gymie', 'manage-products', 'delete-product'])
                                                                                        <a href="#" class="delete-record"
                                                                                            data-dependency="{{ $dependency }}"
                                                                                            data-dependency-message="You have members by from this product, u can't delete it"
                                                                                            data-delete-url="{{ url('products/' . $product->id . '/archive') }}"
                                                                                            data-record-id="{{$product->id}}">
                                                                                            Delete product
                                                                                        </a>
                                                                                        @endpermission
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                        @endforeach
                                    </tbody>


                                </table>

                                <!-- Pagination -->
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="gymie_paging_info">
                                            Showing page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="gymie_paging pull-right">
                                            {!! str_replace('/?', '?', $products->appends(Input::Only('search'))->render()) !!}
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
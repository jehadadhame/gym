@extends('app')

@section('content')

    <div class="rightside bg-grey-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel no-border">
                        <div class="panel-title">
                            <div class="panel-head font-size-20">Enter details of the product</div>
                        </div>

                        {!! Form::model($product, ['method' => 'POST','action' => ['ProductController@update',$product->id],'id'=>'productsform']) !!}

                        @include('products.form',['submitButtonText' => 'Update'])

                        {!! Form::Close() !!}

                        </form>

                    </div>
                </div>
            </div>
        </div>

        @stop
        @section('footer_scripts')
            <script src="{{ URL::asset('assets/js/product.js') }}" type="text/javascript"></script>
@stop
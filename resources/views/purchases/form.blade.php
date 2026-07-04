<?php use Carbon\Carbon; ?>

<div class="row">
    <div class="col-sm-5">
        <div class="form-group">
            <?php
            $members = App\Member::where('status', '=', '1')->get();

            $memberArray = [];
            foreach ($members as $member) {
                $memberArray[$member['id']] = $member['member_code'].' - '.$member['name'];
            }
            ?>
            {!! Form::label('member_id','كود / اسم العضو') !!}
            {!! Form::select('member_id',$memberArray,null,['class'=>'form-control selectpicker show-tick show-menu-arrow','id'=>'member_id','data-live-search' => 'true']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        {!! Form::label('product_0','المنتج') !!}
    </div>


    <div class="col-sm-1">
        <label>&nbsp;</label><br/>
    </div>
</div> <!-- / Row -->
<div id="servicesContainer">
    <div class="row">
        <div class="col-sm-5">
            <div class="form-group product-id">
                <?php $products = App\Product::where('status', '=', '1')->get(); ?>

                <select id="product_0" name="product[0][id]" class="form-control selectpicker show-tick show-menu-arrow childProduct" data-live-search="true"
                        data-row-id="0">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->amount }}" 
                                data-row-id="0">{{ $product->product_name }} </option>
                    @endforeach
                </select>
                <div class="product-price">
                    {!! Form::hidden('product[0][price]','', array('id' => 'price_0')) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-1">
            <div class="form-group">
                    <span class="btn btn-sm btn-danger pull-left hide remove-service">
                      <i class="fa fa-times"></i>
                    </span>
            </div>
        </div>

    </div> <!-- / Row -->
</div>
<div class="row">
    <div class="col-sm-2 pull-left">
        <div class="form-group">
            <span class="btn btn-sm btn-primary pull-left" id="addPurchase">إضافة منتج آخر</span>
        </div>
    </div>
</div>

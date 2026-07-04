<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $products = Product::excludeArchive()->search('"' . $request->input("search") . '"')->paginate(30);
        $count = $products->total();
        return view("products.index", compact('products', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view("products.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_code' => 'unique:products,product_code',
            'product_name' => 'unique:products,product_name',
        ]);

        $product = new Product($request->all());

        $product->createdBy()->associate(Auth::user());
        $product->updatedBy()->associate(Auth::user());

        $product->save();

        flash()->success('Product was successfully created');

        return redirect('products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        dd($product);
        return view('products.show', compact("product"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {

        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update($request->all());
        $product->updatedBy()->associate(Auth::user());
        $product->save();
        flash()->success('Product details were successfully updated');

        return redirect('products/all');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Routing\Redirector
     */
    public function archive($id)
    {
        Product::destroy($id);
        return redirect('products/all');
    }
}

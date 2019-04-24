<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    var $validationRules = [
        'code' => 'required|unique:products',
        'name' => 'required|max:250',
        'url' => 'required|unique:products'
    ];
    var $validationMessages = [
        'code.unique' => 'The code already exist',
        'url.unique' => 'The url already exist',
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function index()
    {
        return response()->json(Product::all(), 200);
    }
    public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product, 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules, $this->validationMessages);
        if($validator->fails()) return response()->json($validator->errors(), 400);
        
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->validationRules, $this->validationMessages);
        if($validator->fails()) return response()->json($validator->errors(), 400);
        $product = Product::find($id);
        if( ! $product ) {
            return response()->json("Product not found", 404);
        }
        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->url = $request->input('url');
        $product->save();
        
        return response()->json($product, 200);
    }
    public function delete(Request $request, Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
    
    public function errors()
    {
        return response()->json(['message'=>'Payment is required'], 501);
    }
}
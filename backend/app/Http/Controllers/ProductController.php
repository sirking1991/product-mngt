<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    var $validationRules = [
        'code' => 'bail|required',
        'name' => 'bail|required',
        'url' => 'bail|required'
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
    public function __construct() {}

    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    public function duplicateNames()
    {
        $product = DB::table('duplicate_names')->get();
        return response()->json($product, 200);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) return response()->json('Product not found', 404);

        return response()->json($product, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules, $this->validationMessages);
        if ($validator->fails()) 
            return response()->json($validator->errors(), 400);
        
        try {
            $product = Product::create($request->all());
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json("Conflict with existing ".$this->checkConflict($e), 409);     // conflict
        }

        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->validationRules, $this->validationMessages);
        if ($validator->fails()) 
            return response()->json($validator->errors(), 400);

        $product = Product::find($id);
        if (!$product) return response()->json('Product not found', 404);

        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->url = $request->input('url');

        try {
            $product->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json("Conflict with existing ".$this->checkConflict($e), 409);     // conflict
        }
        
        return response()->json($product, 200);
    }

    private function checkConflict($e)
    {
        return false !== strpos($e->errorInfo[2],'products_code_unique') ? 'CODE' : 'URL';
    }

    public function delete(Request $request, $id)
    {
        $product = Product::find($id);
        
        if (!$product) return response()->json('Product not found', 404);

        $product->delete();
        return response()->json(null, 204);
    }
    
}
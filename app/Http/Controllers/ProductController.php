<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator};

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          =>  'required|string|max:50',
            'price'         =>  'required|numeric|min:0',
            'location'      =>  'required|string|max:50',
            'cover_image'   =>  'nullable|mimes:jpg,png,jpeg|max:1024',
            'platform_id'   =>  'required|integer|exists:plateforms,id',
            'category_id'   =>  'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }

        $product    =   Product::create($request->all());

        return response()->json(['status' => true, 'message' => "Ads created successfully", 'data' => $product], 201);
    }
}

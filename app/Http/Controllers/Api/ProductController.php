<?php

namespace App\Http\Controllers\Api;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{
     /**
     * 
     * List available Categories
     *
     */
    public function index()
    {
         return ProductResource::collection(Product::paginate(25));
    }

    /**
     *  Add a Category
     *
	 * 	@param  \Illuminate\Http\Request  $request
     * 	@return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if (!$request->hasFile('img')) {
         return response()->json(['upload_file_not_found'], 404);
       }  
         $image = $request->file('img');
         if(!$image->isValid()) {
           return response()->json(['invalid_file_upload'], 404);
          }

         $fileName = $image->getClientOriginalName();
         $destinationPath = public_path() . '/uploads/';
         $image->move($destinationPath, $fileName);
         
         
         $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'img' => $filename
      ]);
      
        return new ProductResource($product);

    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
         return new ProductResource($product);
    }

    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->only(['name','category_id', 'price']));
        return new ProductResource($product);
    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['product successfully deleted'], 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
     /**
     * 
     * List available Categories
     *
     */
    public function index()
    {
         return CategoryResource::collection(Category::paginate(25));
    }

    /**
     *  Add a Category
     *
	 * 	@param  \Illuminate\Http\Request  $request
     * 	@return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $category = Category::create([
            'name' => $request->name
      ]);

      return new CategoryResource($category);
    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
         return new CategoryResource($category);
    }

    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->update($request->only(['name']));
        return new CategoryResource($category);
    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

      return response()->json(['category successfully deleted'], 204);
    }
}

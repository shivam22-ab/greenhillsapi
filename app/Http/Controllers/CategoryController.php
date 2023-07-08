<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        if($categories->count()>0)
        {
            return response()->json([
                'status'=>200,
                'categories'=>$categories,
            ],200);
        }
        return response()->json([
            'staus'=>404,
            'categories'=>'No record found',
        ],404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try{
            $validator = Validator::make($request->all(),[
                'name'=>'required|string|unique:categories,name',
                'description'=>'required|string'
            ]);
            if($validator->fails()){
                return response()->json([
                    'status'=>422,
                    'errors'=>$validator->messages()
                ],422);
            }
            $slug = Str::slug($request->name);
            $category = Category::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'slug'=>$slug
            ]);
            if($category){
                return response()->json([
                    'status'=>200,
                    'message'=>'Category created successfully'
                ],200);
            }
            else{
                return response()->json([
                    'status'=>500,
                    'message'=>'Something went wrong'
                ],500);
            }

       }
       catch(\Exception $e){
            return response()->json([
                'status'=>500,
                'message'=>'Something went wrong'
            ],500);
       }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try{
            $category = Category::where('slug',$slug)->first();
            if($category)
            {
                return response()->json([
                    'status'=>200,
                    'category'=>$category,
                ],200);
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>'No such record found',
                ],404);
            }

        }
        catch(\Exception $e){
            return response()->json([
                'status'=>500,
                'message'=>'Something went wrong'
            ],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        try{
            $category = Category::where('slug',$slug)->first();
            $validator = Validator::make($request->all(),[
                'name'=>'required','string',Rule::unique('categories')->ignore($category->id),
                'description'=>'required|string'
            ]);
            $slug = Str::slug($request->name);
            if($validator->fails())
            {
                return response()->json([
                    'status'=>422,
                    'errors'=>$validator->messages()
                ],422);
            }
            $update = $category->update([
                'name'=>$request->name,
                'description'=>$request->description,
                'slug'=>$slug
            ]);
            if($update){
                return response()->json(['status'=>200,
                'message'=>'Updated successfully'],200);
            }
            else{
                return response()->json(['status'=>500,
                'message'=>'Updated successfully'],500);
            }
       }
       catch(\Exception $e){
            return response()->json([
                'status'=>500,
                'message'=>'Something went wrong'
            ],500);
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        try{
            $category = Category::where('slug',$slug)->first();
            $destroy = $category->delete();
            if($destroy){
                return response()->json([
                    'status'=>200,
                    'message'=>'Deleted successfully'
                ],200);
            }
            else{
                return response()->json([
                    'status'=>500,
                    'message'=>'Something went wrong'
                ],200);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>500,
                'message'=>'Something went wrong'
            ],200);
        }
    }
}

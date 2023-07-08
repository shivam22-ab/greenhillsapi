<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validate\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service = Service::simplePaginate(5);
        if($service->count() >0)
        {
            return response()->json([
                'status'=>200,
                'services'=>$service
            ],200);
        }
       return response()->json([
        'status'=>404,
        'message'=>'Services not found'
       ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'name'=>'required|unique:services,name',
                'image'=>'required|image|mimes:png,jpg,svg,gif',
                'description'=>'required',
                'price'=>'required',
                'category_id'=>'required',
            ]);
            if($validator->fails())
            {
                return response()->json([
                    'status'=>422,
                    $error =>$validator->messages()
                ],422);
            }
            //save image
            $image = $request->image;
            if($request->hasFile('image'))
            {
                $image = $request->file('image')->store('images','public');
            }
            $slug = Str::slug($request->name. '.'.'-'.$request->category_id);
            $service = Service::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'image'=> $image,
                'location'=>$request->location,
                'time'=>$request->time,
                'price'=>$request->price,
                'category_id'=>$request->category_id,
                'slug'=>$slug
            ]);
            if($service)
            {
                return response()->json([
                    'status'=>200,
                    'message'=>'Service created successfully'
                ],200);
            }
            return response()->json([
                'status'=>500,
                'message'=>'Something went wrong...'
            ],500);

        }
        catch(\Exception $e)
        {
            return response()->json([
                'status'=>500,
                'message'=>'Something went wrong...'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        try{
            $service = Service::where('slug',$slug)->first();
            $delete = $service->delete();
             //delete existing image
            $image_path = public_path('storage/'.$content->image);
            $imagePath = public_path('storage/' . $service->image);
            if (!empty($service->image) && file_exists($imagePath)) {
                unlink($imagePath);
            }
            if($delete)
            {
                return response()->json([
                    'status'=>200,
                    'messsage'=>'Service deleted successfully'
                ],200);
            }
            return response()->json([
                'status'=>500,
                'messsage'=>'Something went wrong...'
            ],500);

        }
        catch(\Exception $e)
        {
            return response()->json([
                'status'=>500,
                'messsage'=>'Something went wrong...'
            ],500);
        }
    }
}

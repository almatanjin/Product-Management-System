<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $rules = [     
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    
        $validatedData = $request->validate($rules);
        try 
        {
            
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images', $filename, 'public');

            return response()->json([
                'url' => $path,
                'success' => true,
                'message' => 'Image Uploaded'
            ], 201);
        }
        catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'error' => 'Failed to upload',
                'success' => false,
                'message' => $e->getMessage(),
            ], 500); 
        }
    }
}

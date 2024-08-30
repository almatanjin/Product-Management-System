<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Category;
Use App\Models\Product;
Use App\Models\Feature;
use App\Models\CategoryProduct;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index()
    {
       
        $products = Product::all();
        $products->load('category', 'feature');
        return response()->json(['products' => $products]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'required|string|max:2048',
            'features' => 'required|array',
            'features.*.description' => 'required|string|max:255',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ];
    
        $validatedData = $request->validate($rules);

        DB::beginTransaction();
    
        try 
        {
            $product = Product::create([
                'name' => $request->input('name'),
                'image' => $request->input('image'),
            ]);
    
            $features = $request->input('features', '[]');
            $categories = $request->input('categories', '[]');
           
            $product->category()->attach($categories);
            
            // Create features
            foreach ($features as $featureData) {
                if (isset($featureData['description']) && !empty($featureData['description'])) {
                    Feature::create([
                        'product_id' => $product->id,
                        'description' => $featureData['description'],
                    ]);
                }
            }
    
            DB::commit();

            $product->load('category');

    
            return response()->json([
                'data' => $product,
                'success' => true,
                'message' => 'Product and features created successfully'
            ], 201);
        }
        catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'error' => 'Failed to create',
                'success' => false,
                'message' => $e->getMessage(),
            ], 500); 
        }
    }

    public function show(string $id)
    {
        try {
            $product = Product::findOrFail($id);
    
            $product->load('category', 'feature');

            return response()->json([
                'data' => $product,
                'success' => true,
                'message' => 'Product retrieved successfully',
            ], 200); 

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404); 
        }
    }

    public function update(Request $request, string $id)
{
    // Define your validation rules
    $validatedData = $request->validate([
        'name' => 'nullable|string|max:255',
        'image' => 'nullable|string|max:2048',
    ]);

    try {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Update the product with validated data
        $product->update($validatedData);

        // Return a success response
        return response()->json([
            'data' => $product,
            'success' => true,
            'message' => 'Product updated successfully',
        ], 200);

    } catch (ModelNotFoundException $e) {
        // Handle the case where the product is not found
        return response()->json([
            'error' => 'Product not found',
            'success' => false,
            'message' => $e->getMessage(),
        ], 404);

    } catch (\Exception $e) {
        // Handle any other exceptions
        return response()->json([
            'error' => 'Failed to update product',
            'success' => false,
            'message' => $e->getMessage(),
        ], 500); // Use 500 for general server errors
    }
}



    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            CategoryProduct::where('product_id', $id)->delete();
    
            $product->delete();

            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Product not found',
                'success' => false,
                'message' => $e->getMessage()
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to delete the product',
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function getCategories()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories]);
    }

    public function deleteCategories(string $id)
    {
        try {
            DB::beginTransaction();

            $category = Category::findOrFail($id);

            $productIds = CategoryProduct::where('category_id', $id)->pluck('product_id')->unique();


            CategoryProduct::where('category_id', $id)->delete();
    
            Product::whereIn('id', $productIds)->delete();

            $category->delete();
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'category deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'category not found',
                'success' => false,
                'message' => $e->getMessage()
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to delete the category',
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Category::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }
        
        // Apply active filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        // Apply sorting
        $sortField = $request->input('sort_field', 'sort_order');
        $sortDirection = $request->input('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);
        
        // Paginate results
        $categories = $query->paginate($request->input('per_page', 15));
        
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:1024' // max 1MB
        ]);
        
        // Set boolean fields explicitly to avoid null values
        $validated['is_active'] = $request->boolean('is_active', true);
        
        // Set user who created the category
        if (auth()->check()) {
            $validated['created_by'] = auth()->id();
            $validated['updated_by'] = auth()->id();
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
            $validated['image_mime'] = $request->file('image')->getMimeType();
            $validated['image_size'] = $request->file('image')->getSize();
        }
        
        // Create the category
        $category = Category::create($validated);
        
        return new CategoryResource($category);
    }
    public function canDelete(Category $category)
    {
        $canDelete = true;
        $reason = '';
        
        // Check for associated products
        if ($category->products()->count() > 0) {
            $canDelete = false;
            $reason = 'This category has associated products. Please remove or reassign these products before deleting.';
        }
        
        return response()->json([
            'can_delete' => $canDelete,
            'reason' => $reason
        ]);
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        
        // Add image URL if present
        if ($category->image) {
            $category->image_url = asset('storage/' . $category->image);
        }

        return new CategoryResource($category);
    }

    /**
     * Update the specified category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:1024' // max 1MB
        ]);
        
        // Set boolean fields explicitly to avoid null values
        $validated['is_active'] = $request->boolean('is_active', true);
        
        // Set user who updated the category
        if (auth()->check()) {
            $validated['updated_by'] = auth()->id();
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
            $validated['image_mime'] = $request->file('image')->getMimeType();
            $validated['image_size'] = $request->file('image')->getSize();
        } else if ($request->boolean('remove_image')) {
            // Remove image if requested
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $validated['image'] = null;
            $validated['image_mime'] = null;
            $validated['image_size'] = null;
        }
        
        // Update the category
        $category->update($validated);
        
        return new CategoryResource($category);
    }

    /**
     * Remove the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with associated products',
                'errors' => ['general' => ['This category has associated products. Please reassign or delete those products first.']]
            ], 422);
        }
        
        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        // Delete the category
        $category->delete();
        
        return response()->noContent();
    }

    /**
     * Get a list of all categories for dropdown selection.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json($categories);
    }
}